<?php

namespace Concrete\Job;

use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Entity\Express\Entry;
use Concrete\Core\Entity\Site\Site;
use Concrete\Core\Express\ObjectManager;
use Concrete\Core\Express\Search\Index\EntityIndex;
use Concrete\Core\File\File;
use Concrete\Core\Job\JobQueue;
use Concrete\Core\Job\JobQueueMessage;
use Concrete\Core\Job\QueueableJob;
use Concrete\Core\Page\Page;
use Concrete\Core\Search\Index\IndexManagerInterface;
use Concrete\Core\Search\Index\IndexObjectProvider;
use Concrete\Core\Support\Facade\Facade;
use Concrete\Core\User\User;
use Punic\Misc as PunicMisc;

class IndexSearchAll extends QueueableJob
{
    // A flag for clearing the index
    public const CLEAR = '-1';

    public const CLEAR_EXPRESS_ENTITY = '-2';

    public $jQueueBatchSize = 50;

    public $jNotUninstallable = 1;

    public $jSupportsQueue = true;

    /**
     * @var array The result from the last queue item
     */
    protected $result;

    protected $clearTable = true;

    /**
     * @var \Concrete\Core\Search\Index\IndexManagerInterface
     */
    protected $indexManager;

    /**
     * @var \Concrete\Core\Database\Connection\Connection
     */
    protected $connection;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var IndexObjectProvider
     */
    protected $dataProvider;

    public function __construct(
        IndexManagerInterface $indexManager,
        Connection $connection,
        ObjectManager $objectManager,
        IndexObjectProvider $dataProvider
    ) {
        $this->indexManager = $indexManager;
        $this->connection = $connection;
        $this->objectManager = $objectManager;
        $this->dataProvider = $dataProvider;
    }

    public function getJobName()
    {
        return t('Index Search Engine - All');
    }

    public function getJobDescription()
    {
        return t('Empties the page search index and reindexes all pages.');
    }

    public function start(JobQueue $queue)
    {
        if ($this->clearTable) {
            // Send a "clear" queue item to clear out the index
            $queue->send(self::CLEAR);
        }

        try {
            $i = 0;
            $transactionSize = 5000;
            foreach ($this->queueMessages() as $i => $message) {
                if ($i % $transactionSize === 0) {
                    $this->connection->beginTransaction();
                }

                $queue->send($message);

                if (($i + 1) % $transactionSize === 0) {
                    $this->connection->commit();
                }
            }

            if (($i + 1) % $transactionSize !== 0) {
                $this->connection->commit();
            }
        } catch (\Exception $e) {
            $this->connection->rollback();
            throw $e;
        }
    }

    public function processQueueItem(JobQueueMessage $msg)
    {
        $index = $this->indexManager;

        // Handle a "clear" message
        if (substr($msg->body, 0, 2) === '-2') {
            $this->clearExpressEntityIndex(substr($msg->body, 2));
        } elseif ($msg->body == self::CLEAR) {
            $this->clearIndex($index);
        } else {
            $body = $msg->body;

            $message = substr($body, 1);
            $type = $body[0];

            $map = [
                'P' => Page::class,
                'U' => User::class,
                'F' => File::class,
                'S' => Site::class,
                'E' => Entry::class,
            ];

            if (isset($map[$type])) {
                $index->index($map[$type], $message);
            } elseif ($type === 'R') {
                // Store this result, this is likely the last item.
                $this->result = json_decode($message);
            }
        }
    }

    public function finish(JobQueue $q)
    {
        if ($this->result) {
            list($pages, $users, $files, $sites, $objects, $entries) = $this->result;

            return t(
                'Index performed on: %s',
                PunicMisc::joinAnd([
                    t2('%d page', '%d pages', $pages),
                    t2('%d user', '%d users', $users),
                    t2('%d file', '%d files', $files),
                    t2('%d site', '%d sites', $sites),
                    t2('%d Express object', '%d Express objects', $objects),
                    t2('%d Express entry', '%d Express entries', $entries),
                ])
            );
        }

        return t('Indexed pages, users, files, sites and express data.');
    }

    /**
     * Messages to add to the queue.
     *
     * @return \Iterator
     */
    protected function queueMessages()
    {
        $pages = $users = $files = $sites = $objects = $entries = 0;

        foreach ($this->dataProvider->fetchExpressObjects() as $id) {
            yield self::CLEAR_EXPRESS_ENTITY . $id;
            $objects++;
        }

        foreach ($this->dataProvider->fetchPages() as $id) {
            yield "P{$id}";
            $pages++;
        }
        foreach ($this->dataProvider->fetchUsers() as $id) {
            yield "U{$id}";
            $users++;
        }
        foreach ($this->dataProvider->fetchFiles() as $id) {
            yield "F{$id}";
            $files++;
        }
        foreach ($this->dataProvider->fetchSites() as $id) {
            yield "S{$id}";
            $sites++;
        }

        foreach ($this->dataProvider->fetchExpressEntries() as $id) {
            yield "E{$id}";
            $entries++;
        }

        // Yield the result very last
        yield 'R' . json_encode([$pages, $users, $files, $sites, $objects, $entries]);
    }

    protected function clearExpressEntityIndex($id)
    {
        $object = $this->objectManager->getObjectByID($id);
        if ($object) {
            $app = Facade::getFacadeApplication();
            $index = $app->make(EntityIndex::class, ['entity' => $object]);
            $index->clear();
        }
    }

    /**
     * Clear out all indexes.
     *
     * @param $index
     */
    protected function clearIndex($index)
    {
        $index->clear(Page::class);
        $index->clear(User::class);
        $index->clear(File::class);
        $index->clear(Site::class);
    }
}

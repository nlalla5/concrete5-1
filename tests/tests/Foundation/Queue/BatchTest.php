<?php

namespace Concrete\Tests\Foundation\Queue;

use Concrete\Core\Entity\Queue\Batch;
use Concrete\Core\File\Command\RescanFileBatchProcessFactory;
use Concrete\Core\File\Command\RescanFileCommand;
use Concrete\Core\Entity\File\File;
use Concrete\Core\Foundation\Command\Dispatcher;
use Concrete\Core\Foundation\Command\DispatcherFactory;
use Concrete\Core\Foundation\Queue\Batch\BatchFactory;
use Concrete\Core\Foundation\Queue\Batch\BatchProgressUpdater;
use Concrete\Core\Foundation\Queue\Batch\Processor;
use Concrete\Core\Foundation\Queue\Batch\Response\BatchProcessorResponse;
use Concrete\Core\Foundation\Queue\Batch\Response\BatchProcessorResponseFactory;
use Concrete\Core\Support\Facade\Facade;
use Concrete\TestHelpers\Database\ConcreteDatabaseTestCase;
use Concrete\Tests\TestCase;
use Doctrine\ORM\EntityRepository;
use Queue;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;

class BatchTest extends TestCase
{

    protected function buildFile($fID)
    {
        $file = $this->getMockBuilder(File::class)
            ->disableOriginalConstructor()
            ->getMock();
        $file->expects($this->any())
            ->method('getFileID')
            ->willReturn($fID);
        return $file;
    }

    public function testBatchProcessor()
    {
        $app = Facade::getFacadeApplication();
        $factory = new RescanFileBatchProcessFactory();

        $batchFactory = $this->getMockBuilder(BatchFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $dispatcherFactory = $this->getMockBuilder(DispatcherFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $batchProcessUpdater = $this->getMockBuilder(BatchProgressUpdater::class)
            ->disableOriginalConstructor()
            ->getMock();
        $dispatcher = $this->getMockBuilder(Dispatcher::class)
            ->disableOriginalConstructor()
            ->getMock();
        $dispatcherFactory->expects($this->once())
            ->method('getDispatcher')
            ->willReturn($dispatcher);

        $response = new BatchProcessorResponse();

        $batchProcessorResponseFactory = $this->getMockBuilder(BatchProcessorResponseFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $batchProcessorResponseFactory->expects($this->once())
            ->method('createResponse')
            ->willReturn($response);

        $batch = new Batch();

        $batchFactory->expects($this->once())
            ->method('createOrGetBatch')
            // Be sure to pass the method argument(s)
            // as array, even if you only have one
            // argument!
            ->with(
                $this->equalTo('rescan_file')
            )
            ->willReturn($batch);

        $processor = new Processor($dispatcherFactory, $batchFactory, $batchProcessUpdater, $batchProcessorResponseFactory);

        $file1 = $this->buildFile(1);
        $file2 = $this->buildFile(4);
        $file3 = $this->buildFile(7);
        $files = [$file1, $file2, $file3];

        $response = $processor->process($factory, $files);

        $this->assertInstanceOf(BatchProcessorResponse::class, $response);

    }
}


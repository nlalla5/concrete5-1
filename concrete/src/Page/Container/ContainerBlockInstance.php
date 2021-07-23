<?php

namespace Concrete\Core\Page\Container;

use Concrete\Core\Block\Block;
use Concrete\Core\Entity\Page\Container\Instance;
use Doctrine\ORM\EntityManager;

class ContainerBlockInstance
{

    /**
     * @var Block 
     */
    protected $block;

    /**
     * @var Instance 
     */
    protected $instance;

    /**
     * @var EntityManager 
     */
    protected $entityManager;
    
    public function __construct(Block $block, Instance $instance, EntityManager $entityManager)
    {
        $this->instance = $instance;
        $this->block = $block;
        $this->entityManager = $entityManager;
    }

    /**
     * @return Block
     */
    public function getBlock(): Block
    {
        return $this->block;
    }

    /**
     * @return Instance
     */
    public function getInstance(): Instance
    {
        return $this->instance;
    }

    /**
     * Runs when the file is about to be included. 
     */
    public function startRender()
    {
        return;
    }

    /**
     * Runs when render is completed. This way we can trigger area recompute in a performant way.
     */
    public function endRender()
    {
        if (!$this->instance->areaAreasComputed()) {
            // Since this happens at the end, presumably we HAVE recomputed the areas at this point, because
            // that happens within the individual ContainerArea::display() methods. So let's flip this to
            // true so that we don't perpetually have to recompute the areas.
            $this->instance->setAreasAreComputed(true);
            $this->entityManager->persist($this->instance);
            $this->entityManager->flush();
        }
    }
    
}

<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

abstract class AbstractService
{
    /** @var EntityManager */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Saving entity object to DB
     *
     * @param object $entityObject
     */
    public function saveEntity($entityObject)
    {
        $this->em->persist($entityObject);
        $this->em->flush();
    }
}

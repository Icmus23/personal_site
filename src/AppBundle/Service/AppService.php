<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use AppBundle\Service\AbstractService;

class AppService extends AbstractService
{
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

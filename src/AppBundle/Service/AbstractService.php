<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

abstract class AbstractService
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
}

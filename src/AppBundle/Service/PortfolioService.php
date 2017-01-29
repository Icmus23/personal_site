<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Portfolio;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Service\AbstractService;

class PortfolioService extends AbstractService
{

    private $repository;

    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
        $this->repository = $this->em->getRepository('AppBundle:Portfolio');
    }

    public function getAllPortfolio()
    {
        return $this->repository->findAll();
    }

    public function getPortfolioById($id)
    {
        $portfolio = $this->repository->find($id);

        if (!$portfolio) {
            throw new NotFoundHttpException('Portfolio with that id not found');
        }

        return $portfolio;
    }
}

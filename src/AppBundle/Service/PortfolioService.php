<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Portfolio;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Service\AbstractService;

class PortfolioService extends AbstractService
{
    /** @var \Doctrine\ORM\EntityRepository */
    private $repository;

    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
        $this->repository = $this->em->getRepository('AppBundle:Portfolio');
    }

    /**
     * Get all portfolio elements
     *
     * @return array
     */
    public function getAllPortfolio()
    {
        return $this->repository->findAll();
    }

    /**
     * Get portfolio by Id
     *
     * @param int $id
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return Portfolio|object
     */
    public function getPortfolioById($id)
    {
        $portfolio = $this->repository->find($id);

        if (!$portfolio) {
            throw new NotFoundHttpException('Portfolio with that id not found');
        }

        return $portfolio;
    }
}

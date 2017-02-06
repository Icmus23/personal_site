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

    private $uploadDirectory;

    private $uploaderService;

    public function __construct(EntityManager $em, $uploaderService, $uploadDirectory)
    {
        parent::__construct($em);
        $this->repository = $this->em->getRepository('AppBundle:Portfolio');
        $this->uploadDirectory = $uploadDirectory;
        $this->uploaderService = $uploaderService;
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

    /**
     * Delete Portfolio entity with related files
     *
     * @param $entity Portfolio
     */
    public function delete($entity)
    {
        foreach ($entity->getFiles() as $file) {
            $filePath = $this->uploadDirectory.$file->getUrl();
            $this->uploaderService->removeFile($filePath);
            $this->em->remove($file);
        }
        $this->em->remove($entity);
        $this->em->flush();
    }
}

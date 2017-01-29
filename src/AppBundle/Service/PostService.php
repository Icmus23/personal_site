<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Post;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Service\AbstractService;

class PostService extends AbstractService
{
    /** @var \Doctrine\ORM\EntityRepository */
    private $repository;

    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
        $this->repository = $this->em->getRepository('AppBundle:Post');
    }

    /**
     * Getting post by $id
     *
     * @param int $id
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return Post|Object
     */
    public function getPostById($id)
    {
        $post = $this->repository->find($id);

        if (!$post) {
            throw new NotFoundHttpException('There is no post with that Id!');
        }

        return $post;
    }

    /**
     * Get all posts
     *
     * @return array
     */
    public function getAllPosts()
    {
        return $this->repository->findAll();
    }

    /**
     * Get all active posts
     *
     * @return array
     */
    public function getAllActivePosts()
    {
        $posts = $this->repository->findBy(
            [
                'active' => true
            ]
        );

        return $posts;
    }
}

<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Post;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Service\AbstractService;

class PostService extends AbstractService
{
    /**
     * Getting post with $id
     *
     * @param int $id
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return Post|object
     */
    public function getPostById($id)
    {
        $repository = $this->em->getRepository('AppBundle:Post');
        $post = $repository->find($id);

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
        return $this->em->getRepository('AppBundle:Post')->findAll();
    }

    /**
     * Get all active posts
     *
     * @return array
     */
    public function getAllActivePosts()
    {
        $repository = $this->em->getRepository('AppBundle:Post');

        $posts = $repository->findBy(
            [
                'active' => true
            ]
        );

        return $posts;
    }
}

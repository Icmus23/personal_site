<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Post;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    /**
     * @Route("/blog", name="blog")
     * @Method({"GET"})
     */
    public function blogAction()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Post');

        $posts = $repository->findBy(
            [
                'active' => true
            ]
        );

        return $this->render(
            'blog/blog.html.twig',
            [
                'posts' => $posts
            ]
        );
    }
}

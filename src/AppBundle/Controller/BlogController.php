<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller
{
    /**
     * @Route("/blog", name="blog")
     * @Method({"GET"})
     */
    public function blogAction()
    {
        $posts = $this->get('post_service')->getAllActivePosts();

        return $this->render(
            'blog/blog.html.twig',
            [
                'posts' => $posts
            ]
        );
    }
}

<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Post;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    /**
     * @Route("/blog", name="blog")
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

    /**
     * @Route("/add_post")
     */
    public function addPostAction()
    {
        $em = $this->getDoctrine()->getManager();

        $post = new Post();
        $post->setTitle('Second Blog Post');
        $post->setSubtitle('Second Blog Post in upcomming renew site');
        $post->setText('Blog post text, describes a lot of interesting stuff');
        $post->setActive(false);
        $post->setCreated(new \DateTime("now"));
        $post->setUpdated(new \DateTime("now"));

        $em->persist($post);

        $em->flush();

        return new Response();
    }
}

<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\AddPost;
use AppBundle\Entity\Post;

class AdminController extends Controller
{
    /**
     * @Route("/admin/", name="admin")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function adminAction()
    {
        return $this->render('admin/admin.html.twig');
    }

    /**
     * @Route("/admin/blog", name="admin_blog")
     * @Method({"GET"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function blogAction()
    {
        $addPostForm = $this->createForm(AddPost::class);

        return $this->render(
            'admin/blog/blog.html.twig',
            [
                'add_post_form' => $addPostForm->createView()
            ]
        );
    }

    /**
     * @Route("/admin/blog")
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addPostAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(AddPost::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreated(new \DateTime("now"));

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
        }

        return $this->redirectToRoute('admin_blog');
    }

    /**
     * @Route("/admin/blog/delete_post/{id}", name="admin_delete_post")
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deletePostAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Post');
        $post = $repository->find($id);

        if (!$post) {
            throw $this->createNotFoundException('There is no post with that Id!');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('blog');
    }
}

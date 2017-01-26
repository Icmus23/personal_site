<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\Post as PostForm;
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
        $posts = $this->get('post_service')->getAllPosts();

        $postForm = $this->createForm(PostForm::class);

        return $this->render(
            'admin/blog/main.html.twig',
            [
                'add_post_form' => $postForm->createView(),
                'posts' => $posts
            ]
        );
    }

    /**
     * @Route("/admin/blog")
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return mixed
     */
    public function addPostAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostForm::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreated(new \DateTime("now"));

            $this->get('post_service')->savePost($post);
        }

        return $this->redirectToRoute('admin_blog');
    }

    /**
     * @Route("/admin/blog/delete_post/{id}", name="admin_delete_post")
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param int $id
     * @return mixed
     */
    public function deletePostAction($id)
    {
        $post = $this->get('post_service')->getPostById($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('blog');
    }

    /**
     * @Route("/admin/blog/edit_post/{id}", name="admin_edit_post")
     * @Method({"GET"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param int $id
     * @return mixed
     */
    public function editPostAction($id)
    {
        $post = $this->get('post_service')->getPostById($id);

        $form = $this->createForm(PostForm::class, $post);

        return $this->render(
            'admin/blog/edit.html.twig',
            [
                'post_form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/admin/blog/edit_post/{id}")
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param int $id
     * @param Request $request
     * @return mixed
     */
    public function updatePostAction($id, Request $request)
    {
        $post = $this->get('post_service')->getPostById($id);
        $form = $this->createForm(PostForm::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUpdated(new \DateTime("now"));

            $this->get('post_service')->savePost($post);
        }

        return $this->redirectToRoute('admin_blog');
    }

    /**
     * @Route("/admin/portfolio", name="admin_portfolio")
     * @Method({"GET"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function portfolioAction()
    {
        return $this->render(
            'admin/portfolio/main.html.twig',
            []
        );
    }
}

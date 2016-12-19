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
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->findAll();

        $postForm = $this->createForm(PostForm::class);

        return $this->render(
            'admin/blog/blog.html.twig',
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

            $this->savePost($post);
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
        $post = $this->getPostById($id);

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
        $post = $this->getPostById($id);

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
        $post = $this->getPostById($id);
        $form = $this->createForm(PostForm::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUpdated(new \DateTime("now"));

            $this->savePost($post);
        }

        return $this->redirectToRoute('admin_blog');
    }

    /**
     * Getting post with $id
     *
     * @param int $id
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return Post|object
     */
    private function getPostById($id)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:Post');
        $post = $repository->find($id);

        if (!$post) {
            throw $this->createNotFoundException('There is no post with that Id!');
        }

        return $post;
    }

    /**
     * Saving post object to DB
     *
     * @param Post $post
     */
    private function savePost(Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();
    }
}

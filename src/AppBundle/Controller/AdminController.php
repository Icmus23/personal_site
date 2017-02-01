<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\Post as PostForm;
use AppBundle\Form\Portfolio as PortfolioForm;
use AppBundle\Entity\Post;
use AppBundle\Entity\Portfolio;
use AppBundle\Entity\Picture;

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

            $this->get('app_service')->saveEntity($post);
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

            $this->get('app_service')->saveEntity($post);
        }

        return $this->redirectToRoute('blog');
    }

    /**
     * @Route("/admin/portfolio", name="admin_portfolio")
     * @Method({"GET"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function portfolioAction()
    {
        $portfolioForm = $this->createForm(PortfolioForm::class);

        return $this->render(
            'admin/portfolio/main.html.twig',
            [
                'portfolio_form' => $portfolioForm->createView()
            ]
        );
    }

    /**
     * @Route("/admin/portfolio")
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addPortfolioAction(Request $request)
    {
        $portfolio = new Portfolio();
        $form = $this->createForm(PortfolioForm::class, $portfolio);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $portfolio->setCreated(new \DateTime("now"));
            $this->get('app_service')->saveEntity($portfolio);

            $files = $portfolio->getFiles();

            $this->get('uploader')->uploadFiles(
                $portfolio->getFiles(),
                $this->getParameter('portfolio_files_upload_directory'),
                $portfolio
            );
        }

        return $this->redirectToRoute('admin_portfolio');
    }

    /**
     * @Route("/admin/blog/edit_portfolio/{id}", name="admin_edit_portfolio")
     * @Method({"GET"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param int $id
     * @return mixed
     */
    public function editPortfolioAction($id)
    {
        $portfolio = $this->get('portfolio_service')->getPortfolioById($id);

        $form = $this->createForm(PortfolioForm::class, $portfolio);

        return $this->render(
            'admin/portfolio/edit.html.twig',
            [
                'portfolio_form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/admin/blog/delete_portfolio/{id}", name="admin_delete_portfolio")
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param int $id
     * @return mixed
     */
    public function deletePortfolioAction($id)
    {
        $portfolio = $this->get('portfolio_service')->getPortfolioById($id);

        if ($portfolio) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($portfolio);
            $em->flush();
        }

        return $this->redirectToRoute('portfolio');
    }

    /**
     * @Route("/admin/blog/edit_portfolio/{id}")
     * @Method({"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param int $id
     * @param Request $request
     * @return mixed
     */
    public function updatePortfolioAction($id, Request $request)
    {
        $portfolio = $this->get('portfolio_service')->getPortfolioById($id);
        $form = $this->createForm(PortfolioForm::class, $portfolio);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $portfolio->setUpdated(new \DateTime("now"));

            $this->get('app_service')->saveEntity($portfolio);
        }

        return $this->redirectToRoute('portfolio');
    }
}

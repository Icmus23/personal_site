<?php

namespace Tests\AppBundle\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Entity\Post;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostServiceTest extends KernelTestCase
{
    private $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testGetPostById()
    {
        $post = static::$kernel->getContainer()->get('post_service')->getPostById(3);

        $this->assertEquals(Post::class, get_class($post));
    }

    public function testGetPostByIdWithWrongId()
    {
        $this->setExpectedException(NotFoundHttpException::class);

        $post = static::$kernel->getContainer()->get('post_service')->getPostById(1231312);
    }
}

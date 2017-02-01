<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Picture;
use AppBundle\Service\AbstractService;
use AppBundle\Service\AppService;

class Uploader extends AbstractService
{
    /** @var AppBundle\Service\AppService */
    private $appService;

    public function __construct(EntityManager $em, AppService $appService)
    {
        parent::__construct($em);
        $this->appService = $appService;
    }

    public function uploadFile($file, $directory, $parent)
    {
        // generate new filename
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        // move uploaded file to server
        $file->move(
            $directory,
            $fileName
        );

        //create entity
        $picture = new Picture();
        $picture->setUrl($fileName);
        $picture->setParent($parent);
        $picture->setParentType('portfolio');
        $picture->setCreated(new \Datetime('now'));

        $this->appService->saveEntity($picture);
    }

    public function uploadFiles($files, $directory, $parent)
    {
        foreach ($files as $file) {
            $this->uploadFile(
                $file,
                $directory,
                $parent
            );
        }
    }
}

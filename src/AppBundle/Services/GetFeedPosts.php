<?php

namespace AppBundle\Services;

use AppBundle\Entity\Feed;
use AppBundle\Entity\Post;

class GetFeedPosts
{
    private $doctrine;

    public function __construct($doctrine) {
        $this->doctrine = $doctrine;
    }

    public function getAllPosts() {
        $em = $this->doctrine->getManager();

        $queryResult = $this->doctrine
            ->getRepository('AppBundle:Post')
            ->findBy(array(), array('postDate' => 'DESC'));

        return $queryResult;
    }
}
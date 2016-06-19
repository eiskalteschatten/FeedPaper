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

        $queryResult = $em->createQueryBuilder()
                ->select('p.id, p.postImage, p.postTitle, p.postPreview, p.feed, p.postDate')
                ->from('AppBundle:Post', 'p')
                ->orderBy('p.postDate', 'DESC')
                ->getQuery()
                ->getResult();

        return $queryResult;
    }
}
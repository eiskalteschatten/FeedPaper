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
            ->select('p.id, p.postImage, p.postTitle, p.postPreview, p.feed, p.postDate, p.isRead')
            ->from('AppBundle:Post', 'p')
            ->orderBy('p.postDate', 'DESC')
            ->getQuery()
            ->getResult();

        return $queryResult;
    }

    public function getSinglePost($id, $locale='en') {
        $queryResult = $this->doctrine
            ->getRepository('AppBundle:Post')
            ->findOneBy(array('id' => $id));

        $formatter = new \IntlDateFormatter($locale, \IntlDateFormatter::LONG, \IntlDateFormatter::LONG);
        $date = $formatter->format($queryResult->getPostDate());

        $result = array(
            'id' => $queryResult->getId(),
            'title' => $queryResult->getPostTitle(),
            'url' => $queryResult->getPostUrl(),
            'date' => $date,
            'author' => $queryResult->getPostAuthor(),
            'content' => $queryResult->getPostContent()
        );

        return $result;
    }

    public function markSinglePostAsRead($id) {
        $em = $this->doctrine->getManager();
        $entity = $em->getRepository('AppBundle:Post')->find($id);

        $entity->setIsRead(true);

        $em->flush();
    }
}
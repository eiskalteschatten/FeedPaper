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
            ->select('p.id, p.postImage, p.postTitle, p.postPreview, p.feed, p.postDate, p.isRead, f.feedName')
            ->from('AppBundle:Post', 'p')
            ->join('AppBundle:Feed', 'f', \Doctrine\ORM\Query\Expr\Join::WITH, 'f.id = p.feed')
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

        $feedResult = $this->doctrine
            ->getRepository('AppBundle:Feed')
            ->findOneBy(array('id' => $queryResult->getFeed()));

        $result = array(
            'id' => $queryResult->getId(),
            'title' => $queryResult->getPostTitle(),
            'url' => $queryResult->getPostUrl(),
            'date' => $date,
            'author' => $queryResult->getPostAuthor(),
            'content' => $queryResult->getPostContent(),
            'feed' => $feedResult->getFeedName()
        );

        return $result;
    }

    public function markSinglePostAsRead($id) {
        $em = $this->doctrine->getManager();
        $entity = $em->getRepository('AppBundle:Post')->find($id);

        $entity->setIsRead(true);

        $em->flush();
    }

    public function markSinglePostAsUnread($id) {
        $em = $this->doctrine->getManager();
        $entity = $em->getRepository('AppBundle:Post')->find($id);

        $entity->setIsRead(false);

        $em->flush();
    }

    public function markAllAsRead() {
        $em = $this->doctrine->getManager();
        $entities = $em->getRepository('AppBundle:Post')->findAll();

        foreach($entities as $entity) {
            $entity->setIsRead(true);
        }

        $em->flush();
    }
}
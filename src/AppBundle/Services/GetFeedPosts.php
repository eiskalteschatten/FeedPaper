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
            ->select('p.id, p.postImage, p.postTitle, p.postPreview, p.feed, f.folder, p.postDate, p.isRead, f.feedName')
            ->from('AppBundle:Post', 'p')
            ->join('AppBundle:Feed', 'f', \Doctrine\ORM\Query\Expr\Join::WITH, 'f.id = p.feed')
            ->addOrderBy('p.isRead', 'ASC')
            ->addOrderBy('p.postDate', 'DESC')
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

    public function markAllAsRead($ids) {
        $em = $this->doctrine->getManager();

        foreach ($ids as $id) {
            $entity = $em->getRepository('AppBundle:Post')->findOneBy(array('id' => $id));
            $entity->setIsRead(true);
        }

        $em->flush();
    }
}
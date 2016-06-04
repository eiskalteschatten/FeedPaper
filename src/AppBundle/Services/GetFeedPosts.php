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
        $date = new \DateTime("now");

        $queryResult = $this->doctrine
            ->getRepository('AppBundle:Feed')
            ->findAll();

        try {
            foreach ($queryResult as $result) {
                $url = $result->getFeedUrl();
                $feed = $result->getId();

                $content = file_get_contents($url);
                $rss = new \SimpleXmlElement($content);

                foreach($rss->channel->item as $entry) {
                    $title = substr($entry->title, 0, 255);
                    $url = $entry->link;

                    $postResult = $this->doctrine
                        ->getRepository('AppBundle:Post')
                        ->findOneBy(array('postTitle' => $title, 'postUrl' => $url));

                    if (empty($postResult)) {
                        $author = $entry->children('dc', true)->creator;
                        $content = $entry->children('content', true)->encoded;

                        if (empty($content)) {
                            $content = $entry->description;
                        }

                        $post = new Post();
                        $post->setDateFetched($date);
                        $post->setPostTitle($title);
                        $post->setPostUrl($url);
                        $post->setPostDate(new \DateTime($entry->pubDate));
                        $post->setIsRead(false);
                        $post->setFeed($feed);
                        $post->setPostAuthor($author);
                        $post->setPostContent($content);
                        $post->setPostImage($this->extractFirstImage($content));

                        $em = $this->doctrine->getManager();
                        $em->persist($post);
                        $em->flush();
                    }
                }
            }
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    private function extractFirstImage($content) {
        preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $images);

        if (!empty($images) && !empty($images[1])) {
            $image = $images[1][0];

            if ($image != null && filter_var($image, FILTER_VALIDATE_URL) && $this->isRealImage($image)) {
                return $image;
            }
        }

        return "";
    }

    private function isRealImage($image) {
        $imageExts = array('jpg', 'jpeg', 'png', 'gif', 'svg');

        foreach ($imageExts as $ext) {
            if (stristr($image, $ext)) {
                return true;
            }
        }

        return false;
    }

    private function purgeOldPosts() {

    }
}
<?php

namespace AppBundle\Services;

use AppBundle\Entity\Feed;
use AppBundle\Entity\Post;

class FetchFeedPosts
{
    private $doctrine;

    public function __construct($doctrine) {
        $this->doctrine = $doctrine;
    }

    public function fetchAllPosts() {
        $date = new \DateTime("now");
        $em = $this->doctrine->getManager();

        $feedQueryResult = $this->doctrine
            ->getRepository('AppBundle:Feed')
            ->findAll();

        try {
            foreach ($feedQueryResult as $result) {
                $url = $result->getFeedUrl();
                $feed = $result->getId();

                $fileContents = file_get_contents($url);
                $rss = new \SimpleXmlElement($fileContents);

                $titlesInFeed = array();

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

                        $uniCodeRegEx = '/[^\x20-\x7f]/';
                        $title = preg_replace($uniCodeRegEx, '', $title);
                        $content = preg_replace($uniCodeRegEx, '', $content);
                        $preview = $this->createPostPreview($content);

                        $post = new Post();
                        $post->setDateFetched($date);
                        $post->setPostTitle($title);
                        $post->setPostUrl($url);
                        $post->setPostDate(new \DateTime($entry->pubDate));
                        $post->setIsRead(false);
                        $post->setFeed($feed);
                        $post->setPostAuthor($author);
                        $post->setPostContent($content);
                        $post->setPostPreview($preview);
                        $post->setPostImage($this->extractFirstImage($content));

                        $em->persist($post);

                        $titlesInFeed[] = $title;
                    }
                }

                $this->purgeOldPosts($feed, $rss, $titlesInFeed);
            }
        }
        catch(\Exception $e) {
            throw $e;
        }
        finally {
            $em->flush();
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

    private function purgeOldPosts($feedId, $rss, $titlesInFeed) {
        $postResult = $this->doctrine
            ->getRepository('AppBundle:Post')
            ->findBy(array('feed' => $feedId, 'isRead' => true));

        $em = $this->doctrine->getEntityManager();

        foreach ($postResult as $result) {
            if (!in_array($result->getPostTitle(), $titlesInFeed)) {
                $em->remove($result);
            }
        }

        $em->flush();
    }

    private function createPostPreview($content) {
        $previewStripped = strip_tags($content);
        $preview = substr($previewStripped, 0, 75);

        if (strlen($previewStripped) > 75) {
            $preview .= '...';
        }

        return $preview;
    }

    // Todo: update feed icon and title
}
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 */
class Post
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="post_title", type="string", length=255)
     */
    private $postTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="post_url", type="string", length=255)
     */
    private $postUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="post_date", type="datetime")
     */
    private $postDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fetched", type="datetime")
     */
    private $dateFetched;

    /**
     * @var string
     *
     * @ORM\Column(name="post_image", type="string", length=255, nullable=true)
     */
    private $postImage;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_read", type="boolean")
     */
    private $isRead;

    /**
     * @var int
     *
     * @ORM\Column(name="feed", type="integer")
     */
    private $feed;

    /**
     * @var string
     *
     * @ORM\Column(name="post_author", type="string", length=255, nullable=true)
     */
    private $postAuthor;

    /**
     * @var string
     *
     * @ORM\Column(name="post_content", type="text", nullable=true)
     */
    private $postContent;

    /**
     * @var string
     *
     * @ORM\Column(name="post_preview", type="text", nullable=true)
     */
    private $postPreview;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set postTitle
     *
     * @param string $postTitle
     * @return Post
     */
    public function setPostTitle($postTitle)
    {
        $this->postTitle = $postTitle;

        return $this;
    }

    /**
     * Get postTitle
     *
     * @return string 
     */
    public function getPostTitle()
    {
        return $this->postTitle;
    }

    /**
     * Set postUrl
     *
     * @param string $postUrl
     * @return Post
     */
    public function setPostUrl($postUrl)
    {
        $this->postUrl = $postUrl;

        return $this;
    }

    /**
     * Get postUrl
     *
     * @return string 
     */
    public function getPostUrl()
    {
        return $this->postUrl;
    }

    /**
     * Set postDate
     *
     * @param \DateTime $postDate
     * @return Post
     */
    public function setPostDate($postDate)
    {
        $this->postDate = $postDate;

        return $this;
    }

    /**
     * Get dateFetched
     *
     * @return \DateTime
     */
    public function getDateFetched()
    {
        return $this->dateFetched;
    }

    /**
     * Set dateFetched
     *
     * @param \DateTime $dateFetched
     * @return Post
     */
    public function setDateFetched($dateFetched)
    {
        $this->dateFetched = $dateFetched;

        return $this;
    }

    /**
     * Get postDate
     *
     * @return \DateTime
     */
    public function getPostDate()
    {
        return $this->postDate;
    }

    /**
     * Set postImage
     *
     * @param string $postImage
     * @return Post
     */
    public function setPostImage($postImage)
    {
        $this->postImage = $postImage;

        return $this;
    }

    /**
     * Get postImage
     *
     * @return string 
     */
    public function getPostImage()
    {
        return $this->postImage;
    }

    /**
     * Set isRead
     *
     * @param boolean $isRead
     * @return Post
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return boolean 
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * Set feed
     *
     * @param integer $feed
     * @return Post
     */
    public function setFeed($feed)
    {
        $this->feed = $feed;

        return $this;
    }

    /**
     * Get feed
     *
     * @return integer
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * Set postAuthor
     *
     * @param string $postAuthor
     * @return Post
     */
    public function setPostAuthor($postAuthor)
    {
        $this->postAuthor = $postAuthor;

        return $this;
    }

    /**
     * Get postAuthor
     *
     * @return string 
     */
    public function getPostAuthor()
    {
        return $this->postAuthor;
    }

    /**
     * Set postContent
     *
     * @param string $postContent
     * @return Test
     */
    public function setPostContent($postContent)
    {
        $this->postContent = $postContent;

        return $this;
    }

    /**
     * Get postContent
     *
     * @return string
     */
    public function getPostContent()
    {
        return $this->postContent;
    }

    /**
     * Set postPreview
     *
     * @param string $postPreview
     * @return Test
     */
    public function setPostPreview($postPreview)
    {
        $this->postPreview = $postPreview;

        return $this;
    }

    /**
     * Get postPreview
     *
     * @return string
     */
    public function getPostPreview()
    {
        return $this->postPreview;
    }
}

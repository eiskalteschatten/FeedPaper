<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Feed
 *
 * @ORM\Table(name="feed")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FeedRepository")
 */
class Feed
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
     * @ORM\Column(name="feed_name", type="string", length=255)
     */
    private $feedName;

    /**
     * @var string
     *
     * @ORM\Column(name="feed_url", type="string", length=255)
     */
    private $feedUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime")
     */
    private $dateModified;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;

    /**
     * @var string
     *
     * @ORM\Column(name="icon_url", type="string", length=255, nullable=true)
     */
    private $iconUrl;

    /**
     * @var int
     *
     * @ORM\Column(name="folder", type="integer", nullable=true)
     */
    private $folder;


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
     * Set feedName
     *
     * @param string $feedName
     * @return Feed
     */
    public function setFeedName($feedName)
    {
        $this->feedName = $feedName;

        return $this;
    }

    /**
     * Get feedName
     *
     * @return string 
     */
    public function getFeedName()
    {
        return $this->feedName;
    }

    /**
     * Set feedUrl
     *
     * @param string $feedUrl
     * @return Feed
     */
    public function setFeedUrl($feedUrl)
    {
        $this->feedUrl = $feedUrl;

        return $this;
    }

    /**
     * Get feedUrl
     *
     * @return string 
     */
    public function getFeedUrl()
    {
        return $this->feedUrl;
    }

    /**
     * Set dateModified
     *
     * @param \DateTime $dateModified
     * @return Feed
     */
    public function setDateModified($dateModified)
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    /**
     * Get dateModified
     *
     * @return \DateTime 
     */
    public function getDateModified()
    {
        return $this->dateModified;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Feed
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set iconUrl
     *
     * @param string $iconUrl
     * @return Feed
     */
    public function setIconUrl($iconUrl)
    {
        $this->iconUrl = $iconUrl;

        return $this;
    }

    /**
     * Get iconUrl
     *
     * @return string 
     */
    public function getIconUrl()
    {
        return $this->iconUrl;
    }

    /**
     * Set folder
     *
     * @param integer $folder
     * @return Feed
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get folder
     *
     * @return integer 
     */
    public function getFolder()
    {
        return $this->folder;
    }
}

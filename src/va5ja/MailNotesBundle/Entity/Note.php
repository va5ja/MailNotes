<?php

namespace va5ja\MailNotesBundle\Entity;

/**
 * Note
 */
class Note
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var \DateTime
     */
    private $datetime;

    /**
     * @var \va5ja\MailNotesBundle\Entity\Category
     */
    private $category;


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
     * Set title
     *
     * @param string $title
     *
     * @return Note
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Note
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Note
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     *
     * @return Note
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Set category
     *
     * @param \va5ja\MailNotesBundle\Entity\Category $category
     *
     * @return Note
     */
    public function setCategory(\va5ja\MailNotesBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \va5ja\MailNotesBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }
}


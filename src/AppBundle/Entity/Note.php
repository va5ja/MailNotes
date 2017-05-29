<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Note
 *
 * @ORM\Table(name="note", indexes={@ORM\Index(name="note_category_id", columns={"category_id"})})
 * @ORM\Entity
 */
class Note {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="content", type="text", length=65535, nullable=true)
	 */
	private $content;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="datetime", type="datetime", nullable=true)
	 */
	private $datetime;

	/**
	 * @var \AppBundle\Entity\Category
	 *
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category")
	 * @ORM\JoinColumns({
	 *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
	 * })
	 */
	private $category;


	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set content
	 *
	 * @param string $content
	 *
	 * @return Note
	 */
	public function setContent($content) {
		$this->content = $content;

		return $this;
	}

	/**
	 * Get content
	 *
	 * @return string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Set datetime
	 *
	 * @param \DateTime $datetime
	 *
	 * @return Note
	 */
	public function setDatetime($datetime) {
		$this->datetime = $datetime;

		return $this;
	}

	/**
	 * Get datetime
	 *
	 * @return \DateTime
	 */
	public function getDatetime() {
		return $this->datetime;
	}

	/**
	 * Set category
	 *
	 * @param \AppBundle\Entity\Category $category
	 *
	 * @return Note
	 */
	public function setCategory(\AppBundle\Entity\Category $category = null) {
		$this->category = $category;

		return $this;
	}

	/**
	 * Get category
	 *
	 * @return \AppBundle\Entity\Category
	 */
	public function getCategory() {
		return $this->category;
	}
}

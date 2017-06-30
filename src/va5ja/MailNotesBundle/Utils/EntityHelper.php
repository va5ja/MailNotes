<?php

namespace va5ja\MailNotesBundle\Utils;

use va5ja\MailNotesBundle\Entity\Category;

/**
 * Class EntityHelper
 * @package va5ja\MailNotesBundle\Utils
 */
class EntityHelper
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * EntityHelper constructor.
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(\Doctrine\ORM\EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $categories = $categoryRepository->findAll();

        return $categories;
    }
}
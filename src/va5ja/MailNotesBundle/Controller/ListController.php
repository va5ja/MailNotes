<?php

namespace va5ja\MailNotesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use va5ja\MailNotesBundle\Entity\Category;
use va5ja\MailNotesBundle\Entity\Note;

class ListController extends Controller
{

    public function listAction($page, $_format)
    {
        $noteRepository = $this->getDoctrine()->getRepository(Note::class);
        $notes = $noteRepository->findAll();

        return $this->render(
            'List/list.'.$_format.'.twig',
            [
                'categoryid' => false,
                'notes' => $notes,
            ]
        );
    }

    public function categoryAction($categorySlug, $page, $_format)
    {
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);
        $category = $categoryRepository->findOneBy(['slug' => $categorySlug]);

        $noteRepository = $this->getDoctrine()->getRepository(Note::class);
        $notes = $noteRepository->findby(['category' => $category]);

        return $this->render(
            'List/list.'.$_format.'.twig',
            [
                'categoryid' => $category->getId(),
                'notes' => $notes,
            ]
        );
    }
}
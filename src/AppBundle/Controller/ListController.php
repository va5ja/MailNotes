<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ListController extends Controller
{

    public function listAction($page, $_format)
    {
        $categoryRepository = $this->getDoctrine()->getRepository('AppBundle:Category');
        $categories = $categoryRepository->findAll();

        $noteRepository = $this->getDoctrine()->getRepository('AppBundle:Note');
        $notes = $noteRepository->findAll();

        return $this->render(
            'list/list.'.$_format.'.twig',
            [
                'categoryid' => false,
                'categories' => $categories,
                'notes' => $notes,
            ]
        );
    }

    public function categoryAction($categorySlug, $page, $_format)
    {
        $categoryRepository = $this->getDoctrine()->getRepository('AppBundle:Category');
        $category = $categoryRepository->findOneBy(['slug' => $categorySlug]);
        $categories = $categoryRepository->findAll();

        $noteRepository = $this->getDoctrine()->getRepository('AppBundle:Note');
        $notes = $noteRepository->findby(['category' => $category]);

        return $this->render(
            'list/list.'.$_format.'.twig',
            [
                'categoryid' => $category->getId(),
                'categories' => $categories,
                'notes' => $notes,
            ]
        );
    }
}
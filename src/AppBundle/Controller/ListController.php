<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ListController extends Controller {

	public function listAction($page, $_format) {
		$categoryRepository = $this->getDoctrine()->getRepository('AppBundle:Category');
		$categories = $categoryRepository->findAll();

		$noteRepository = $this->getDoctrine()->getRepository('AppBundle:Note');
		$notes = $noteRepository->findAll();

		return $this->render('list/list.' . $_format . '.twig', [
			'categories' => $categories,
			'notes' => $notes
		]);
	}
}
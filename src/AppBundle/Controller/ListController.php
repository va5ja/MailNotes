<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ListController extends Controller {

	public function listAction($page, $_format) {
		$repository = $this->getDoctrine()->getRepository('AppBundle:Note');
		$notes = $repository->findAll();

		return $this->render('list/list.' . $_format . '.twig', [
			'notes' => $notes
		]);
	}
}
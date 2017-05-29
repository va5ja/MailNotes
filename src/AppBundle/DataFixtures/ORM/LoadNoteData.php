<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Note;

class LoadNoteData implements FixtureInterface, ContainerAwareInterface
{
	/**
	 * @var ContainerInterface
	 */
	private $container;

	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}

	public function load(ObjectManager $manager)
	{
		$note = new Note();
		$note->setCategory($manager->getRepository('AppBundle:Category')->findOneBy(['name' => 'Series']));
		$note->setContent('The OA
On Netflix');
		$note->setDatetime(new \DateTime());
		$manager->persist($note);
		$manager->flush();

		$note = new Note();
		$note->setCategory($manager->getRepository('AppBundle:Category')->findOneBy(['name' => 'Photography']));
		$note->setContent('https://alison.com/courses/Digital-Photography');
		$note->setDatetime(new \DateTime());
		$manager->persist($note);
		$manager->flush();

		$note = new Note();
		$note->setCategory($manager->getRepository('AppBundle:Category')->findOneBy(['name' => 'Kickstarter']));
		$note->setContent('ONEaudio');
		$note->setDatetime(new \DateTime());
		$manager->persist($note);
		$manager->flush();

		$note = new Note();
		$note->setCategory($manager->getRepository('AppBundle:Category')->findOneBy(['name' => 'Interesting']));
		$note->setContent('https://www.sitepoint.com/7-new-or-interesting-php-packages-to-keep-an-eye-on/');
		$note->setDatetime(new \DateTime());
		$manager->persist($note);
		$manager->flush();
	}
}
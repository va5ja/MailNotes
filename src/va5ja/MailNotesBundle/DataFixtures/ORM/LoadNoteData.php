<?php

namespace va5ja\MailNotesBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use va5ja\MailNotesBundle\Entity\Category;
use va5ja\MailNotesBundle\Entity\Note;

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
		$note->setCategory($manager->getRepository(Category::class)->findOneBy(['name' => 'Series']));
		$note->setTitle('The OA');
        $note->setSlug('the-oa');
		$note->setContent('The OA
On Netflix');
		$note->setDatetime(new \DateTime());
		$manager->persist($note);
		$manager->flush();

		$note = new Note();
		$note->setCategory($manager->getRepository(Category::class)->findOneBy(['name' => 'Photography']));
        $note->setTitle('Digital-Photography');
        $note->setSlug('digital-photography');
		$note->setContent('https://alison.com/courses/Digital-Photography');
		$note->setDatetime(new \DateTime());
		$manager->persist($note);
		$manager->flush();

		$note = new Note();
		$note->setCategory($manager->getRepository(Category::class)->findOneBy(['name' => 'Kickstarter']));
        $note->setTitle('ONEaudio');
        $note->setSlug('oneaudio');
		$note->setContent('ONEaudio');
		$note->setDatetime(new \DateTime());
		$manager->persist($note);
		$manager->flush();

		$note = new Note();
		$note->setCategory($manager->getRepository(Category::class)->findOneBy(['name' => 'Interesting']));
        $note->setTitle('Interesting PHP packages');
        $note->setSlug('interesting-php-packages');
		$note->setContent('https://www.sitepoint.com/7-new-or-interesting-php-packages-to-keep-an-eye-on/');
		$note->setDatetime(new \DateTime());
		$manager->persist($note);
		$manager->flush();
	}
}
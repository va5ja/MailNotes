<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Category;

class LoadCategoryData implements FixtureInterface, ContainerAwareInterface
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
		$category = new Category();
		$category->setName('Series');
		$category->setDatetime(new \DateTime());
		$manager->persist($category);
		$manager->flush();

		$category = new Category();
		$category->setName('Photography');
		$category->setDatetime(new \DateTime());
		$manager->persist($category);
		$manager->flush();

		$category = new Category();
		$category->setName('Kickstarter');
		$category->setDatetime(new \DateTime());
		$manager->persist($category);
		$manager->flush();

		$category = new Category();
		$category->setName('Interesting');
		$category->setDatetime(new \DateTime());
		$manager->persist($category);
		$manager->flush();
	}
}
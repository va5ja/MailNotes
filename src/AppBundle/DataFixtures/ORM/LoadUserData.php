<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\User;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
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
		$user = new User();
		$user->setUsername('admin');
		$encoder = $this->container->get('security.password_encoder');
		$password = $encoder->encodePassword($user, 'admin123');
		$user->setPassword($password);
		$user->setEmail('admin@localhost');
		$user->setEnabled(true);
		$user->setRoles(['ROLE_USER']);

		$manager->persist($user);
		$manager->flush();
	}
}
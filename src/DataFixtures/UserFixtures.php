<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $adminUser = new User();
        $adminUser
            ->setEmail('admin@example.com')
            ->setPassword($this->encoder->hashPassword($adminUser, 'admin@123'))
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($adminUser);

        $user = new User();
        $user
            ->setEmail('user@example.com')
            ->setPassword($this->encoder->hashPassword($user, 'admin@123'))
            ->setRoles(['ROLE_USER']);

        $manager->persist($user);
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    
        private $encoder;

        public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
        {
                $this->encoder = $userPasswordHasherInterface;
        }
        public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('imday16@gmail.com');
        $user->setRoles(["ROLE_USER","ROLE_ADMIN"]);
        $user->setPassword($this->encoder->hashPassword($user, "pass"));
        $manager->persist($user);

        $manager->flush();
    }
}

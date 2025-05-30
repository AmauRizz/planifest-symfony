<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\Category;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $roles = [];
        $categories = [];

        // Create Roles
        for ($i = 0; $i < 2; $i++ )  {
            $adminRole = new Role();
            $adminRole->setName('ROLE_' . $i);
            $roles[] = $adminRole;
            $manager->persist($adminRole);
        }

        // Create Categories
        for ($i = 0; $i < 3; $i++ )  {
            $category = new Category();
            $category->setName('Category ' . $i);
            $categories[] = $category;
            $manager->persist($category);
        }

        $manager->flush();
    }
}
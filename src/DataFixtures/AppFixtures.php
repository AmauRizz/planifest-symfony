<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\Categorie;
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

    /* for prod only (go down for dev one)
    public function load(ObjectManager $manager): void
    {
        // Create predefined Roles
        $roles = ['ROLE_ADMIN', 'ROLE_USER'];
        foreach ($roles as $roleName) {
            $role = new Role();
            $role->setName($roleName);
            $manager->persist($role);
        }

        $categories = [
            "Art",
            "Musique",
            "Spectacle",
            "Cinéma",
            "Exposition",
            "Festival",
            "Conférence",
            "Atelier",
            "Rencontre",
            "Patrimoine",
            "Célébration",
            "Compétition",
            "Numérique",
            "Littérature",
        ];
        foreach ($categories as $categoryName) {
            $category = new Categorie();
            $category->setName($categoryName);
            $manager->persist($category);
        }

        $manager->flush();
    }
    */

    /* for dev only
    */
    public function load(ObjectManager $manager): void
    {
        $roles = [];
        $categories = [];
        $events = [];
        $users = [];
        $images = [];

        // Create Roles
        for ($i = 0; $i < 2; $i++ )  {
            $adminRole = new Role();
            $adminRole->setName('ROLE_' . $i);
            $roles[] = $adminRole;
            $manager->persist($adminRole);
        }

        // Create Categories
        for ($i = 0; $i < 3; $i++ )  {
            $category = new Categorie();
            $category->setName('Category ' . $i);
            $categories[] = $category;
            $manager->persist($category);
        }

        // Create Users
        for ($i = 0; $i < 20; $i++ )  {
            $user = new User();
            $user->setName('User' . $i);
            $user->setEmail('user' . $i . '@example.com');
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password' . $i));
            $user->setRoleEntity($roles[array_rand($roles)]);
            $users[] = $user;
            $manager->persist($user);
        }

        // Create Events
        for ($i = 0; $i < 5; $i++ )  {
            $event = new Event();
            $event->setName('Event ' . $i);
            $event->setDescription('Description of Event ' . $i);
            $event->setStartingDate(new \DateTime());
            $event->setEndingDate(new \DateTime('+1 day'));
            $event->setWebsiteUrl('http://example.com/event-' . $i);
            $event->setAddress($i . 'Rue de l\'exemple EXEMPLE Exemple');
            $event->setCategorieEntity($categories[array_rand($categories)]);
            $events[] = $event;
            $manager->persist($event);
        }

        // Create Images
        for ($i = 0; $i < 5; $i++ )  {
            for ($j = 0; $j < 3; $j++) {
                $image = new Image();
                $image->setSlug('image-' . uniqid() . '.png');
                $image->setName('image ' . $i);
                $image->setSize(rand(100, 1000));
                $image->setEventEntity($events[$i]);
                $images[] = $image;
                $manager->persist($image);
            }
        }

        for ($i = 0; $i < 20; $i++ )  {
            $user = $events[array_rand($events)];

            $image = new Image();
            $image->setSlug('image-' . uniqid() . '.png');
            $image->setName('image ' . $i);
            $image->setSize(rand(100, 1000));
            $image->setUserEntity($users[$i]);
            $images[] = $image;
            $manager->persist($image);
        }

        // Create User-Event relationships
        foreach ($events as $event) {
            foreach ($users as $user) {
                if (rand(0, 1) == 0) {
                    $event->addUser($user);
                }
            }
        }

        $manager->flush();
    }
}
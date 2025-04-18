<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $role1 = new Role();
        $role1->setName('Utilisateur');
        $role1->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($role1);

        $role2 = new Role();
        $role2->setName('Administrateur');
        $role2->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($role2);

        $manager->flush();
    }
}

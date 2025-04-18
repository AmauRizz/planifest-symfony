<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            'Musique',
            'Théâtre',
            'Danse',
            'Expositions',
            'Cinéma',
            'Festival',
            'Conférences',
            'Ateliers',
            'Artisanat',
            'Gastronomie',
            'Littérature',
            'Sports',
            'Jeux Vidéo',
            'Comédie',
            'Street Art',
            'Poesie',
            'Photographie',
            'Mode',
            'Écologie',
            'Technologie',
            'Histoire',
            'Science et Innovation'
        ];

        for ($index = 0; $index < count($categories); $index++) {
            $categorie = new Categorie();
            $categorie->setName($categories[$index]);
            $manager->persist($categorie);
        }

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;

class NelmioAliceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $loader = new NativeLoader();
        
        // Importe le fichier de fixtures et récupère les entités générés
        $entities = $loader->loadFile(__DIR__.'/fixtures.yaml')->getObjects();
        
        // Empile la liste d'objet à enregistrer en BDD
        foreach ($entities as $entity) {
            $manager->persist($entity);
        };

        $manager->flush();
    }
}

// Pour utiliser NelmioAlice : composer require --dev nelmio/alice

// Les fichiers suivants sont modifiés et/ou ajoutés : 
// config/bundles.php : Ajout du bundle dans la liste de ceux qui sont déjà présent dans Symfony
// config/packages/dev/nelmio_alice.yaml : Fichier de configuration dedié à l'environnement de développement
// config/packages/test/nelmio_alice.yaml: Fichier de configuration dedié à l'environnement de test

// J'ai crée un fichier fixtures.yaml

// J'ai crée un nouveau fichier des fixtures avec la commande make:fixtures (NelmioAliceFixtures)

// J'ai rajouté le use qui va me permettre d'utiliser NelmioAlice : use Nelmio\Alice\Loader\NativeLoader;

// J'ai copié/collé le code fourni dans la doc o'clock (toute la partie avant le flush)

// Le code de ce fichier est nécessaire pour DoctrineFixturesBundle, mais les modifications et les précisions se passeront dans le fichier fixtures.yaml

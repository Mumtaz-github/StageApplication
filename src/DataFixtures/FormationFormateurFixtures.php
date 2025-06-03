<?php

namespace App\DataFixtures;

use App\Entity\Formateur;
use App\Entity\Formation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FormationFormateurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Crée formateurs
        $formateurs = [];
        $formateurData = [
            ['nom' => 'François', 'prenom' => 'Regis', 'email' => 'regis@example.com'],
        ];

        foreach ($formateurData as $data) {
            $formateur = new Formateur();
            $formateur->setNom($data['nom']);
            $formateur->setPrenom($data['prenom']);
            $formateur->setEmail($data['email']);
            $manager->persist($formateur);
            // Use full name as key to avoid confusion
            $formateurs[$data['nom'] . ' ' . $data['prenom']] = $formateur;
        }

        // Crée formations linked to formateurs
        $formations = [
            // ['name' => 'CDA', 'formateur' => $formateurs['François Regis']],
           ['name' => $formateurs['François Regis']]

            // ['name' => 'Développement Web et Web Mobile', 'formateur' => $formateurs['François Regis']],
        ];

        foreach ($formations as $data) {
            $formation = new Formation();
            $formation->setNom($data['name']);

            
            $formation->setActifFormation(true);
            $formation->setNumero('001'); 
            $formation->setEtat('Active');
            $formation->setTitreProfessionnel('Titre Pro Example');
            $formation->setNiveau(1);
            $formation->setNbStagiairesPrevisionnel(10);
            $formation->setGroupeRattachement('Groupe A');
            $formation->setDateDebut(new \DateTime('2025-01-01'));
            $formation->setDateFin(new \DateTime('2025-06-30'));

            $formation->addFormateur($data['formateur']);
            $manager->persist($formation);
        }

        $manager->flush();
    }
}


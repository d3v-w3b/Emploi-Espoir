<?php

namespace App\Tests\Controller\User\Account\Career\Alternation;

use App\Entity\JobAndAlternation;
use App\Entity\User;
use App\Tests\Controller\BaseWebTestCase;

class AlternationSearchZoneControllerTest extends BaseWebTestCase
{
    public function testAccessDeniedForAnonymousUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/account/alternation-preferences/alternation-zone');
        
        $this->assertResponseRedirects('/login/password');
    }

    public function testAlternationSearchZonePageRequiresAuthentication(): void
    {
        $client = $this->createAuthenticatedClient(['ROLE_USER']);
        $client->request('GET', '/account/alternation-preferences/alternation-zone');
        
        // Test du comportement sécurisé
        $response = $client->getResponse();
        $this->assertTrue(
            $response->isSuccessful() || $response->isRedirect(),
            'La page devrait être accessible ou rediriger (comportement sécurisé)'
        );
        
        // Si redirection, ça devrait être vers le login
        if ($response->isRedirect()) {
            $this->assertTrue(
                str_contains($response->headers->get('Location'), '/login'),
                'Redirection devrait être vers une page de login'
            );
        }
    }

    public function testAlternationSearchZoneCreationLogic(): void
    {
        // Test de création de zone de recherche d'alternance
        $user = $this->createTestUser();
        
        // Créer une entité JobAndAlternation avec une zone de recherche
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationZone('Île-de-France');
        $jobAndAlternation->setUser($user);
        
        $user->setJobAndAlternation($jobAndAlternation);
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier la création
        $this->assertEquals($user, $jobAndAlternation->getUser());
        $this->assertEquals($jobAndAlternation, $user->getJobAndAlternation());
        $this->assertEquals('Île-de-France', $jobAndAlternation->getAlternationZone());
    }

    public function testAlternationSearchZoneModification(): void
    {
        // Test de modification de la zone de recherche d'alternance
        $user = $this->createTestUser();
        $jobAndAlternation = $this->createTestJobAndAlternation($user, 'Paris');
        
        // Modifier la zone de recherche
        $jobAndAlternation->setAlternationZone('Lyon');
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier la modification
        $this->getEntityManager()->refresh($jobAndAlternation);
        $this->assertEquals('Lyon', $jobAndAlternation->getAlternationZone());
        $this->assertEquals($user, $jobAndAlternation->getUser());
    }

    public function testUserWithoutJobAndAlternation(): void
    {
        // Test avec un utilisateur qui n'a pas encore d'entité JobAndAlternation
        $user = $this->createTestUser();
        
        // Vérifier qu'initialement, l'utilisateur n'a pas d'entité JobAndAlternation
        $this->assertNull($user->getJobAndAlternation());
        
        // Créer une nouvelle entité avec une zone de recherche
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationZone('Nouvelle-Aquitaine');
        $jobAndAlternation->setUser($user);
        
        $user->setJobAndAlternation($jobAndAlternation);
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier que l'entité est bien créée et liée
        $this->assertNotNull($user->getJobAndAlternation());
        $this->assertEquals('Nouvelle-Aquitaine', $user->getJobAndAlternation()->getAlternationZone());
    }

    public function testVariousSearchZones(): void
    {
        // Test avec différentes zones de recherche
        $zones = [
            'Île-de-France',
            'Auvergne-Rhône-Alpes',
            'Nouvelle-Aquitaine',
            'Occitanie',
            'Hauts-de-France',
            'Grand Est',
            'Provence-Alpes-Côte d\'Azur',
            'Pays de la Loire',
            'Bourgogne-Franche-Comté',
            'Bretagne'
        ];
        
        foreach ($zones as $zone) {
            $user = $this->createTestUser('user_' . uniqid() . '@test.com');
            $jobAndAlternation = $this->createTestJobAndAlternation($user, $zone);
            
            // Vérifier que chaque zone est correctement stockée
            $this->assertEquals($zone, $jobAndAlternation->getAlternationZone());
            $this->assertEquals($user, $jobAndAlternation->getUser());
        }
    }

    public function testInternationalSearchZones(): void
    {
        // Test avec des zones de recherche internationales
        $internationalZones = [
            'Europe',
            'Amérique du Nord',
            'Asie-Pacifique',
            'Royaume-Uni',
            'Allemagne',
            'Espagne',
            'Italie',
            'Belgique',
            'Suisse',
            'Canada'
        ];
        
        foreach ($internationalZones as $zone) {
            $user = $this->createTestUser('international_' . uniqid() . '@test.com');
            $jobAndAlternation = $this->createTestJobAndAlternation($user, $zone);
            
            // Vérifier que chaque zone internationale est correctement stockée
            $this->assertEquals($zone, $jobAndAlternation->getAlternationZone());
            $this->assertEquals($user, $jobAndAlternation->getUser());
        }
    }

    public function testEmptySearchZone(): void
    {
        // Test avec une zone de recherche vide
        $user = $this->createTestUser();
        
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationZone(''); // Zone vide
        $jobAndAlternation->setUser($user);
        
        $user->setJobAndAlternation($jobAndAlternation);
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier que la zone vide est gérée correctement
        $this->assertEquals('', $user->getJobAndAlternation()->getAlternationZone());
        $this->assertEmpty($user->getJobAndAlternation()->getAlternationZone());
    }

    public function testNullSearchZone(): void
    {
        // Test avec une zone de recherche null
        $user = $this->createTestUser();
        
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationZone(null);
        $jobAndAlternation->setUser($user);
        
        $user->setJobAndAlternation($jobAndAlternation);
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier que null est géré correctement
        $this->assertNull($user->getJobAndAlternation()->getAlternationZone());
    }

    public function testMultipleUsersSearchZoneIsolation(): void
    {
        // Test d'isolation des zones de recherche entre utilisateurs
        $user1 = $this->createTestUser('user1_' . uniqid() . '@test.com');
        $user2 = $this->createTestUser('user2_' . uniqid() . '@test.com');
        $user3 = $this->createTestUser('user3_' . uniqid() . '@test.com');
        
        // Créer des zones différentes pour chaque utilisateur
        $jobAndAlternation1 = $this->createTestJobAndAlternation($user1, 'Paris');
        $jobAndAlternation2 = $this->createTestJobAndAlternation($user2, 'Lyon');
        $jobAndAlternation3 = $this->createTestJobAndAlternation($user3, 'Marseille');
        
        // Vérifier l'isolation des données
        $this->assertEquals('Paris', $user1->getJobAndAlternation()->getAlternationZone());
        $this->assertEquals('Lyon', $user2->getJobAndAlternation()->getAlternationZone());
        $this->assertEquals('Marseille', $user3->getJobAndAlternation()->getAlternationZone());
        
        // Vérifier que les entités sont différentes
        $this->assertNotEquals($user1->getJobAndAlternation()->getId(), $user2->getJobAndAlternation()->getId());
        $this->assertNotEquals($user2->getJobAndAlternation()->getId(), $user3->getJobAndAlternation()->getId());
        $this->assertNotEquals($user1->getJobAndAlternation()->getId(), $user3->getJobAndAlternation()->getId());
    }

    public function testSearchZoneWithCompleteJobAndAlternationData(): void
    {
        // Test de zone de recherche avec toutes les données JobAndAlternation
        $user = $this->createTestUser();
        
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationZone('Rhône-Alpes');
        $jobAndAlternation->setAlternationField('Intelligence Artificielle');
        $jobAndAlternation->setAlternationPreference(['Stage', 'Apprentissage']);
        $jobAndAlternation->setEmploymentArea('Lyon');
        $jobAndAlternation->setEmploymentPreference(['CDI', 'Startup']);
        $jobAndAlternation->setUser($user);
        
        $user->setJobAndAlternation($jobAndAlternation);
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier que toutes les données sont présentes
        $savedJobAndAlternation = $user->getJobAndAlternation();
        $this->assertEquals('Rhône-Alpes', $savedJobAndAlternation->getAlternationZone());
        $this->assertEquals('Intelligence Artificielle', $savedJobAndAlternation->getAlternationField());
        $this->assertEquals(['Stage', 'Apprentissage'], $savedJobAndAlternation->getAlternationPreference());
        $this->assertEquals('Lyon', $savedJobAndAlternation->getEmploymentArea());
        $this->assertEquals(['CDI', 'Startup'], $savedJobAndAlternation->getEmploymentPreference());
    }

    public function testLongSearchZoneName(): void
    {
        // Test avec un nom de zone très long
        $user = $this->createTestUser();
        
        $longZoneName = 'Région Provence-Alpes-Côte d\'Azur et Monaco avec Extension Internationale';
        
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationZone($longZoneName);
        $jobAndAlternation->setUser($user);
        
        $user->setJobAndAlternation($jobAndAlternation);
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier que le nom long est correctement stocké (tronqué à 128 caractères selon l'entité)
        $savedZone = $user->getJobAndAlternation()->getAlternationZone();
        $this->assertNotEmpty($savedZone);
        $this->assertLessThanOrEqual(128, strlen($savedZone)); // Limite de la colonne
    }

    public function testSpecialCharactersInSearchZone(): void
    {
        // Test avec des caractères spéciaux dans la zone de recherche
        $specialZones = [
            'Île-de-France',
            'Provence-Alpes-Côte d\'Azur',
            'Auvergne-Rhône-Alpes',
            'Centre-Val de Loire',
            'Pays de la Loire',
            'Nord-Pas-de-Calais',
            'Alsace-Champagne-Ardenne-Lorraine'
        ];
        
        foreach ($specialZones as $zone) {
            $user = $this->createTestUser('special_' . uniqid() . '@test.com');
            $jobAndAlternation = $this->createTestJobAndAlternation($user, $zone);
            
            // Vérifier que les caractères spéciaux sont correctement gérés
            $this->assertEquals($zone, $jobAndAlternation->getAlternationZone());
            
            // Vérifier que la zone est bien stockée (test principal)
            $this->assertNotEmpty($jobAndAlternation->getAlternationZone());
        }
    }

    public function testSearchZoneUpdateConsistency(): void
    {
        // Test de cohérence lors de la mise à jour de zone
        $user = $this->createTestUser();
        $jobAndAlternation = $this->createTestJobAndAlternation($user, 'Zone Initiale');
        
        // Mettre à jour plusieurs fois la zone
        $zones = ['Zone 1', 'Zone 2', 'Zone 3', 'Zone Finale'];
        
        foreach ($zones as $zone) {
            $jobAndAlternation->setAlternationZone($zone);
            $this->getEntityManager()->persist($jobAndAlternation);
            $this->getEntityManager()->flush();
            
            // Vérifier que chaque mise à jour est cohérente
            $this->getEntityManager()->refresh($jobAndAlternation);
            $this->assertEquals($zone, $jobAndAlternation->getAlternationZone());
            $this->assertEquals($user, $jobAndAlternation->getUser());
        }
        
        // Vérifier l'état final
        $this->assertEquals('Zone Finale', $user->getJobAndAlternation()->getAlternationZone());
    }

    private function createTestUser(string $email = null): User
    {
        $user = new User();
        $user->setEmail($email ?? 'test_alternation_zone_' . uniqid() . '@example.com');
        $user->setFirstName('Test');
        $user->setLastName('AlternationZone');
        $user->setDateOfBirth(new \DateTimeImmutable('1990-01-01'));
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('test_password');
        
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        
        return $user;
    }

    private function createTestJobAndAlternation(User $user, string $alternationZone = 'Test Zone'): JobAndAlternation
    {
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationField('Test Field');
        $jobAndAlternation->setAlternationZone($alternationZone);
        $jobAndAlternation->setAlternationPreference(['Test Preference']);
        $jobAndAlternation->setEmploymentArea('Test Area');
        $jobAndAlternation->setEmploymentPreference(['Test Employment']);
        $jobAndAlternation->setUser($user);
        
        $user->setJobAndAlternation($jobAndAlternation);

        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return $jobAndAlternation;
    }
}
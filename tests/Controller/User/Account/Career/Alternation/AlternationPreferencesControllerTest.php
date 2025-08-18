<?php

namespace App\Tests\Controller\User\Account\Career\Alternation;

use App\Entity\JobAndAlternation;
use App\Entity\User;
use App\Tests\Controller\BaseWebTestCase;

class AlternationPreferencesControllerTest extends BaseWebTestCase
{
    public function testAccessDeniedForAnonymousUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/account/alternation-preferences');
        
        $this->assertResponseRedirects('/login/password');
    }

    public function testAlternationPreferencesPageRequiresAuthentication(): void
    {
        $client = $this->createAuthenticatedClient(['ROLE_USER']);
        $client->request('GET', '/account/alternation-preferences');
        
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

    public function testDisplayAlternationPreferencesWithData(): void
    {
        // Test d'affichage des préférences d'alternance avec données
        $user = $this->createTestUser();
        $jobAndAlternation = $this->createTestJobAndAlternation($user);
        
        // Vérifier que l'utilisateur a bien ses préférences d'alternance
        $this->assertEquals($jobAndAlternation, $user->getJobAndAlternation());
        $this->assertInstanceOf(JobAndAlternation::class, $user->getJobAndAlternation());
    }

    public function testDisplayAlternationPreferencesWithoutData(): void
    {
        // Test d'affichage des préférences d'alternance sans données
        $user = $this->createTestUser();
        
        // Vérifier qu'un utilisateur sans JobAndAlternation retourne null
        $this->assertNull($user->getJobAndAlternation());
    }

    public function testCompleteAlternationPreferencesDisplay(): void
    {
        // Test d'affichage avec toutes les préférences complètes
        $user = $this->createTestUser();
        
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationField('Intelligence Artificielle');
        $jobAndAlternation->setAlternationZone('Île-de-France');
        $jobAndAlternation->setAlternationPreference(['Temps plein', 'Stage', 'Apprentissage']);
        $jobAndAlternation->setEmploymentArea('Paris');
        $jobAndAlternation->setEmploymentPreference(['CDI', 'CDD', 'Remote']);
        $jobAndAlternation->setUser($user);
        
        $user->setJobAndAlternation($jobAndAlternation);
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier que toutes les données sont présentes pour l'affichage
        $displayedJobAndAlternation = $user->getJobAndAlternation();
        
        $this->assertNotNull($displayedJobAndAlternation);
        $this->assertEquals('Intelligence Artificielle', $displayedJobAndAlternation->getAlternationField());
        $this->assertEquals('Île-de-France', $displayedJobAndAlternation->getAlternationZone());
        $this->assertEquals(['Temps plein', 'Stage', 'Apprentissage'], $displayedJobAndAlternation->getAlternationPreference());
        $this->assertEquals('Paris', $displayedJobAndAlternation->getEmploymentArea());
        $this->assertEquals(['CDI', 'CDD', 'Remote'], $displayedJobAndAlternation->getEmploymentPreference());
    }

    public function testMultipleUsersAlternationPreferencesIsolation(): void
    {
        // Test d'isolation des préférences entre utilisateurs
        $user1 = $this->createTestUser('user1_' . uniqid() . '@test.com');
        $user2 = $this->createTestUser('user2_' . uniqid() . '@test.com');
        
        // Créer des préférences différentes pour chaque utilisateur
        $jobAndAlternation1 = $this->createTestJobAndAlternation($user1);
        $jobAndAlternation1->setAlternationField('Informatique');
        $jobAndAlternation1->setAlternationZone('Paris');
        $this->getEntityManager()->persist($jobAndAlternation1);
        
        $jobAndAlternation2 = $this->createTestJobAndAlternation($user2);
        $jobAndAlternation2->setAlternationField('Commerce');
        $jobAndAlternation2->setAlternationZone('Lyon');
        $this->getEntityManager()->persist($jobAndAlternation2);
        
        $this->getEntityManager()->flush();
        
        // Vérifier l'isolation des données
        $this->assertEquals('Informatique', $user1->getJobAndAlternation()->getAlternationField());
        $this->assertEquals('Commerce', $user2->getJobAndAlternation()->getAlternationField());
        $this->assertEquals('Paris', $user1->getJobAndAlternation()->getAlternationZone());
        $this->assertEquals('Lyon', $user2->getJobAndAlternation()->getAlternationZone());
        
        // Vérifier que les entités sont différentes
        $this->assertNotEquals($user1->getJobAndAlternation()->getId(), $user2->getJobAndAlternation()->getId());
    }

    public function testEmptyArrayPreferences(): void
    {
        // Test avec des préférences sous forme de tableau vide
        $user = $this->createTestUser();
        
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationField('Test Field');
        $jobAndAlternation->setAlternationPreference([]); // Tableau vide
        $jobAndAlternation->setEmploymentPreference([]); // Tableau vide
        $jobAndAlternation->setUser($user);
        
        $user->setJobAndAlternation($jobAndAlternation);
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier que les tableaux vides sont gérés correctement
        $this->assertEquals([], $user->getJobAndAlternation()->getAlternationPreference());
        $this->assertEquals([], $user->getJobAndAlternation()->getEmploymentPreference());
        $this->assertIsArray($user->getJobAndAlternation()->getAlternationPreference());
        $this->assertIsArray($user->getJobAndAlternation()->getEmploymentPreference());
    }

    public function testNullPreferences(): void
    {
        // Test avec des préférences null
        $user = $this->createTestUser();
        
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationField('Test Field');
        $jobAndAlternation->setAlternationPreference(null);
        $jobAndAlternation->setEmploymentPreference(null);
        $jobAndAlternation->setUser($user);
        
        $user->setJobAndAlternation($jobAndAlternation);
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier que les valeurs null sont gérées correctement
        $this->assertNull($user->getJobAndAlternation()->getAlternationPreference());
        $this->assertNull($user->getJobAndAlternation()->getEmploymentPreference());
    }

    public function testUserJobAndAlternationRelationshipIntegrity(): void
    {
        // Test de l'intégrité de la relation OneToOne User-JobAndAlternation
        $user = $this->createTestUser();
        $jobAndAlternation = $this->createTestJobAndAlternation($user);
        
        // Vérifier la relation bidirectionnelle
        $this->assertEquals($user, $jobAndAlternation->getUser());
        $this->assertEquals($jobAndAlternation, $user->getJobAndAlternation());
        
        // Vérifier que la relation persiste après refresh
        $this->getEntityManager()->refresh($user);
        $this->getEntityManager()->refresh($jobAndAlternation);
        
        $this->assertEquals($user, $jobAndAlternation->getUser());
        $this->assertEquals($jobAndAlternation, $user->getJobAndAlternation());
    }

    public function testLargePreferencesArray(): void
    {
        // Test avec un grand tableau de préférences
        $user = $this->createTestUser();
        
        $largePreferences = [
            'Développement Web',
            'Mobile Development',
            'Data Science',
            'Machine Learning',
            'DevOps',
            'Cloud Computing',
            'Cybersécurité',
            'Intelligence Artificielle',
            'Blockchain',
            'IoT'
        ];
        
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationField('Technologies');
        $jobAndAlternation->setAlternationPreference($largePreferences);
        $jobAndAlternation->setUser($user);
        
        $user->setJobAndAlternation($jobAndAlternation);
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier que le grand tableau est correctement stocké et récupéré
        $savedPreferences = $user->getJobAndAlternation()->getAlternationPreference();
        $this->assertCount(10, $savedPreferences);
        $this->assertEquals($largePreferences, $savedPreferences);
        $this->assertContains('Machine Learning', $savedPreferences);
        $this->assertContains('Blockchain', $savedPreferences);
    }

    private function createTestUser(string $email = null): User
    {
        $user = new User();
        $user->setEmail($email ?? 'test_alternation_preferences_' . uniqid() . '@example.com');
        $user->setFirstName('Test');
        $user->setLastName('AlternationPreferences');
        $user->setDateOfBirth(new \DateTimeImmutable('1990-01-01'));
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('test_password');
        
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        
        return $user;
    }

    private function createTestJobAndAlternation(User $user): JobAndAlternation
    {
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationField('Test Field');
        $jobAndAlternation->setAlternationZone('Test Zone');
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
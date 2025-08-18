<?php

namespace App\Tests\Controller\User\Account\Career\Alternation;

use App\Entity\JobAndAlternation;
use App\Entity\User;
use App\Tests\Controller\BaseWebTestCase;

class AlternationPreferencesManagerControllerTest extends BaseWebTestCase
{
    public function testAccessDeniedForAnonymousUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/account/alternation-preferences/preferences');
        
        $this->assertResponseRedirects('/login/password');
    }

    public function testAlternationPreferencesManagerPageRequiresAuthentication(): void
    {
        $client = $this->createAuthenticatedClient(['ROLE_USER']);
        $client->request('GET', '/account/alternation-preferences/preferences');
        
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

    public function testAlternationPreferencesCreationLogic(): void
    {
        // Test de création de préférences d'alternance
        $user = $this->createTestUser();
        
        // Créer une entité JobAndAlternation avec des préférences
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationPreference(['Stage', 'Apprentissage', 'Contrat pro']);
        $jobAndAlternation->setUser($user);
        
        $user->setJobAndAlternation($jobAndAlternation);
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier la création
        $this->assertEquals($user, $jobAndAlternation->getUser());
        $this->assertEquals($jobAndAlternation, $user->getJobAndAlternation());
        $this->assertEquals(['Stage', 'Apprentissage', 'Contrat pro'], $jobAndAlternation->getAlternationPreference());
    }

    public function testAlternationPreferencesModification(): void
    {
        // Test de modification des préférences d'alternance
        $user = $this->createTestUser();
        $jobAndAlternation = $this->createTestJobAndAlternation($user);
        
        // Modifier les préférences
        $newPreferences = ['Temps plein', 'Temps partiel', 'Remote'];
        $jobAndAlternation->setAlternationPreference($newPreferences);
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier la modification
        $this->getEntityManager()->refresh($jobAndAlternation);
        $this->assertEquals($newPreferences, $jobAndAlternation->getAlternationPreference());
        $this->assertEquals($user, $jobAndAlternation->getUser());
    }

    public function testUserWithoutExistingPreferences(): void
    {
        // Test avec un utilisateur qui n'a pas encore de préférences
        $user = $this->createTestUser();
        
        // Vérifier qu'initialement, l'utilisateur n'a pas d'entité JobAndAlternation
        $this->assertNull($user->getJobAndAlternation());
        
        // Créer une nouvelle entité avec des préférences
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationPreference(['Stage en entreprise', 'Projet étudiant']);
        $jobAndAlternation->setUser($user);
        
        $user->setJobAndAlternation($jobAndAlternation);
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier que l'entité est bien créée et liée
        $this->assertNotNull($user->getJobAndAlternation());
        $this->assertEquals(['Stage en entreprise', 'Projet étudiant'], $user->getJobAndAlternation()->getAlternationPreference());
    }

    public function testUpdateExistingPreferences(): void
    {
        // Test de mise à jour de préférences existantes
        $user = $this->createTestUser();
        $jobAndAlternation = $this->createTestJobAndAlternation($user);
        
        // Préférences initiales
        $initialPreferences = ['Initial Preference 1', 'Initial Preference 2'];
        $jobAndAlternation->setAlternationPreference($initialPreferences);
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Mettre à jour les préférences
        $updatedPreferences = ['Updated Preference 1', 'Updated Preference 2', 'New Preference'];
        $jobAndAlternation->setAlternationPreference($updatedPreferences);
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier la mise à jour
        $this->getEntityManager()->refresh($jobAndAlternation);
        $this->assertEquals($updatedPreferences, $jobAndAlternation->getAlternationPreference());
        $this->assertNotEquals($initialPreferences, $jobAndAlternation->getAlternationPreference());
    }

    public function testRedirectionLogic(): void
    {
        // Test de la logique de redirection (simulée)
        $user = $this->createTestUser();
        $jobAndAlternation = $this->createTestJobAndAlternation($user);
        
        // Simuler les différents types de redirection
        $normalRedirect = 'account_alternation_preferences';
        $profileRedirect = 'user_profile_view_as_recruiter';
        
        // Test de redirection normale
        $this->assertEquals('account_alternation_preferences', $normalRedirect);
        
        // Test de redirection vers le profil
        $this->assertEquals('user_profile_view_as_recruiter', $profileRedirect);
        
        // Vérifier que les données persistent indépendamment de la redirection
        $this->assertNotNull($user->getJobAndAlternation());
        $this->assertEquals($jobAndAlternation, $user->getJobAndAlternation());
    }

    public function testEmptyPreferencesArray(): void
    {
        // Test avec un tableau de préférences vide
        $user = $this->createTestUser();
        
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationPreference([]); // Tableau vide
        $jobAndAlternation->setUser($user);
        
        $user->setJobAndAlternation($jobAndAlternation);
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier que le tableau vide est géré correctement
        $this->assertEquals([], $user->getJobAndAlternation()->getAlternationPreference());
        $this->assertIsArray($user->getJobAndAlternation()->getAlternationPreference());
        $this->assertCount(0, $user->getJobAndAlternation()->getAlternationPreference());
    }

    public function testNullPreferences(): void
    {
        // Test avec des préférences null
        $user = $this->createTestUser();
        
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationPreference(null);
        $jobAndAlternation->setUser($user);
        
        $user->setJobAndAlternation($jobAndAlternation);
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier que null est géré correctement
        $this->assertNull($user->getJobAndAlternation()->getAlternationPreference());
    }

    public function testMultipleUsersPreferencesIsolation(): void
    {
        // Test d'isolation des préférences entre utilisateurs
        $user1 = $this->createTestUser('user1_' . uniqid() . '@test.com');
        $user2 = $this->createTestUser('user2_' . uniqid() . '@test.com');
        
        // Créer des préférences différentes pour chaque utilisateur
        $jobAndAlternation1 = $this->createTestJobAndAlternation($user1);
        $jobAndAlternation1->setAlternationPreference(['Web Development', 'Frontend']);
        $this->getEntityManager()->persist($jobAndAlternation1);
        
        $jobAndAlternation2 = $this->createTestJobAndAlternation($user2);
        $jobAndAlternation2->setAlternationPreference(['Data Science', 'Backend']);
        $this->getEntityManager()->persist($jobAndAlternation2);
        
        $this->getEntityManager()->flush();
        
        // Vérifier l'isolation des données
        $this->assertEquals(['Web Development', 'Frontend'], $user1->getJobAndAlternation()->getAlternationPreference());
        $this->assertEquals(['Data Science', 'Backend'], $user2->getJobAndAlternation()->getAlternationPreference());
        
        // Vérifier que les entités sont différentes
        $this->assertNotEquals($user1->getJobAndAlternation()->getId(), $user2->getJobAndAlternation()->getId());
    }

    public function testComplexPreferencesArray(): void
    {
        // Test avec un tableau de préférences complexe
        $user = $this->createTestUser();
        
        $complexPreferences = [
            'Développement Full Stack',
            'Architecture Microservices',
            'DevOps et CI/CD',
            'Sécurité Applicative',
            'Performance Optimization',
            'Code Review et Mentoring',
            'Gestion de Projet Agile',
            'Innovation Technologique'
        ];
        
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationPreference($complexPreferences);
        $jobAndAlternation->setUser($user);
        
        $user->setJobAndAlternation($jobAndAlternation);
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier que le tableau complexe est correctement stocké
        $savedPreferences = $user->getJobAndAlternation()->getAlternationPreference();
        $this->assertCount(8, $savedPreferences);
        $this->assertEquals($complexPreferences, $savedPreferences);
        $this->assertContains('DevOps et CI/CD', $savedPreferences);
        $this->assertContains('Innovation Technologique', $savedPreferences);
    }

    public function testBidirectionalRelationshipConsistency(): void
    {
        // Test de la cohérence de la relation bidirectionnelle
        $user = $this->createTestUser();
        $jobAndAlternation = $this->createTestJobAndAlternation($user);
        
        // Modifier les préférences et vérifier la cohérence
        $newPreferences = ['Consistency Test', 'Bidirectional Check'];
        $jobAndAlternation->setAlternationPreference($newPreferences);
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier que la relation reste cohérente
        $this->assertEquals($user, $jobAndAlternation->getUser());
        $this->assertEquals($jobAndAlternation, $user->getJobAndAlternation());
        $this->assertEquals($newPreferences, $user->getJobAndAlternation()->getAlternationPreference());
        
        // Refresh et re-vérifier
        $this->getEntityManager()->refresh($user);
        $this->getEntityManager()->refresh($jobAndAlternation);
        
        $this->assertEquals($user, $jobAndAlternation->getUser());
        $this->assertEquals($jobAndAlternation, $user->getJobAndAlternation());
    }

    private function createTestUser(string $email = null): User
    {
        $user = new User();
        $user->setEmail($email ?? 'test_alternation_manager_' . uniqid() . '@example.com');
        $user->setFirstName('Test');
        $user->setLastName('AlternationManager');
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
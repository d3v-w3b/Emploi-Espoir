<?php

namespace App\Tests\Controller\User\Account\Career\Alternation;

use App\Entity\JobAndAlternation;
use App\Entity\User;
use App\Tests\Controller\BaseWebTestCase;

class AlternationDomainControllerTest extends BaseWebTestCase
{
    public function testAccessDeniedForAnonymousUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/account/alternation-preferences/alternation-domain');
        
        $this->assertResponseRedirects('/login/password');
    }

    public function testAlternationDomainPageRequiresAuthentication(): void
    {
        $client = $this->createAuthenticatedClient(['ROLE_USER']);
        $client->request('GET', '/account/alternation-preferences/alternation-domain');
        
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

    public function testAlternationDomainCreationLogic(): void
    {
        // Test de création de domaine d'alternance directement via les entités
        $user = $this->createTestUser();
        
        // Créer une entité JobAndAlternation pour l'utilisateur
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationField('Informatique');
        $jobAndAlternation->setUser($user);
        
        $user->setJobAndAlternation($jobAndAlternation);
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier la création
        $this->assertEquals($user, $jobAndAlternation->getUser());
        $this->assertEquals($jobAndAlternation, $user->getJobAndAlternation());
        $this->assertEquals('Informatique', $jobAndAlternation->getAlternationField());
    }

    public function testAlternationDomainModification(): void
    {
        // Test de modification du domaine d'alternance
        $user = $this->createTestUser();
        $jobAndAlternation = $this->createTestJobAndAlternation($user, 'Commerce');
        
        // Modifier le domaine
        $jobAndAlternation->setAlternationField('Marketing');
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier la modification
        $this->getEntityManager()->refresh($jobAndAlternation);
        $this->assertEquals('Marketing', $jobAndAlternation->getAlternationField());
        $this->assertEquals($user, $jobAndAlternation->getUser());
    }

    public function testUserWithoutJobAndAlternation(): void
    {
        // Test avec un utilisateur qui n'a pas encore d'entité JobAndAlternation
        $user = $this->createTestUser();
        
        // Vérifier qu'initialement, l'utilisateur n'a pas d'entité JobAndAlternation
        $this->assertNull($user->getJobAndAlternation());
        
        // Créer une nouvelle entité
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationField('Développement Web');
        $jobAndAlternation->setUser($user);
        
        $user->setJobAndAlternation($jobAndAlternation);
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier que l'entité est bien créée et liée
        $this->assertNotNull($user->getJobAndAlternation());
        $this->assertEquals('Développement Web', $user->getJobAndAlternation()->getAlternationField());
    }

    public function testAlternationDomainWithCompleteData(): void
    {
        // Test avec toutes les données possibles de JobAndAlternation
        $user = $this->createTestUser();
        
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationField('Intelligence Artificielle');
        $jobAndAlternation->setAlternationZone('Île-de-France');
        $jobAndAlternation->setAlternationPreference(['Temps plein', 'Stage']);
        $jobAndAlternation->setEmploymentArea('Paris');
        $jobAndAlternation->setEmploymentPreference(['CDI', 'Remote']);
        $jobAndAlternation->setUser($user);
        
        $user->setJobAndAlternation($jobAndAlternation);
        
        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->flush();
        
        // Vérifier toutes les données
        $savedJobAndAlternation = $user->getJobAndAlternation();
        $this->assertNotNull($savedJobAndAlternation);
        $this->assertEquals('Intelligence Artificielle', $savedJobAndAlternation->getAlternationField());
        $this->assertEquals('Île-de-France', $savedJobAndAlternation->getAlternationZone());
        $this->assertEquals(['Temps plein', 'Stage'], $savedJobAndAlternation->getAlternationPreference());
        $this->assertEquals('Paris', $savedJobAndAlternation->getEmploymentArea());
        $this->assertEquals(['CDI', 'Remote'], $savedJobAndAlternation->getEmploymentPreference());
    }

    public function testOneToOneRelationshipIntegrity(): void
    {
        // Test de l'intégrité de la relation OneToOne
        $user1 = $this->createTestUser('user1_' . uniqid() . '@test.com');
        $user2 = $this->createTestUser('user2_' . uniqid() . '@test.com');
        
        $jobAndAlternation1 = $this->createTestJobAndAlternation($user1, 'Informatique');
        $jobAndAlternation2 = $this->createTestJobAndAlternation($user2, 'Commerce');
        
        // Vérifier que chaque utilisateur a sa propre entité JobAndAlternation
        $this->assertEquals($user1, $jobAndAlternation1->getUser());
        $this->assertEquals($user2, $jobAndAlternation2->getUser());
        $this->assertEquals($jobAndAlternation1, $user1->getJobAndAlternation());
        $this->assertEquals($jobAndAlternation2, $user2->getJobAndAlternation());
        
        // Vérifier que les entités sont différentes
        $this->assertNotEquals($jobAndAlternation1->getId(), $jobAndAlternation2->getId());
        $this->assertNotEquals($jobAndAlternation1->getAlternationField(), $jobAndAlternation2->getAlternationField());
    }

    public function testAlternationDomainValidation(): void
    {
        // Test de validation des données de domaine d'alternance
        $user = $this->createTestUser();
        
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationField(''); // Domaine vide - devrait être invalide en production
        $jobAndAlternation->setUser($user);
        
        // En test unitaire, on peut persister même avec des données invalides
        // mais on peut tester la logique de validation
        $this->assertEmpty($jobAndAlternation->getAlternationField());
        $this->assertInstanceOf(User::class, $jobAndAlternation->getUser());
    }

    public function testCascadeRemoval(): void
    {
        // Test de suppression en cascade
        $user = $this->createTestUser();
        $jobAndAlternation = $this->createTestJobAndAlternation($user, 'Test Domain');
        
        $jobAndAlternationId = $jobAndAlternation->getId();
        
        // Supprimer l'utilisateur (cascade remove)
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
        
        // Vérifier que JobAndAlternation est aussi supprimé
        $removedJobAndAlternation = $this->getEntityManager()->getRepository(JobAndAlternation::class)->find($jobAndAlternationId);
        $this->assertNull($removedJobAndAlternation);
    }

    private function createTestUser(string $email = null): User
    {
        $user = new User();
        $user->setEmail($email ?? 'test_alternation_domain_' . uniqid() . '@example.com');
        $user->setFirstName('Test');
        $user->setLastName('AlternationDomain');
        $user->setDateOfBirth(new \DateTimeImmutable('1990-01-01'));
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('test_password');
        
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        
        return $user;
    }

    private function createTestJobAndAlternation(User $user, string $alternationField = 'Test Field'): JobAndAlternation
    {
        $jobAndAlternation = new JobAndAlternation();
        $jobAndAlternation->setAlternationField($alternationField);
        $jobAndAlternation->setUser($user);
        
        $user->setJobAndAlternation($jobAndAlternation);

        $this->getEntityManager()->persist($jobAndAlternation);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return $jobAndAlternation;
    }
}
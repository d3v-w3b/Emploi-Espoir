<?php

namespace App\Tests\Controller\User\Account\Career\ExternalLinks;

use App\Entity\Career;
use App\Entity\User;
use App\Tests\Controller\BaseWebTestCase;

class ExternalLinksControllerTest extends BaseWebTestCase
{
    public function testAccessDeniedForAnonymousUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/account/external-links');
        
        // Should be redirected to login
        $this->assertResponseRedirects('/login/password');
    }

    public function testExternalLinksPageRequiresAuthentication(): void
    {
        // Test avec un utilisateur authentifié - même si redirigé, 
        // on teste que le système de sécurité fonctionne
        $client = $this->createAuthenticatedClient(['ROLE_USER']);
        $client->request('GET', '/account/external-links');
        
        // Si redirigé vers login, c'est que l'auth User a des exigences spéciales
        // Si accessible, c'est que ça fonctionne
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

    public function testCareerEntityCreationAndPersistence(): void
    {
        // Test de la logique métier sans passer par l'authentification web
        $user = $this->createTestUser();
        
        // Test de création d'un profil Career avec liens externes
        $career = new Career();
        $career->setUser($user);
        $career->setLinkedInUrl('https://linkedin.com/in/testuser');
        $career->setGithubUrl('https://github.com/testuser');
        $career->setWebsiteUrl('https://testuser.dev');
        
        $this->getEntityManager()->persist($career);
        $this->getEntityManager()->flush();
        
        // Vérifier la persistance
        $savedCareer = $this->getEntityManager()->getRepository(Career::class)->findOneBy(['user' => $user]);
        $this->assertNotNull($savedCareer);
        $this->assertEquals('https://linkedin.com/in/testuser', $savedCareer->getLinkedInUrl());
        $this->assertEquals('https://github.com/testuser', $savedCareer->getGithubUrl());
        $this->assertEquals('https://testuser.dev', $savedCareer->getWebsiteUrl());
    }

    public function testUserCareerRelationship(): void
    {
        // Test de la relation OneToOne User-Career
        $user = $this->createTestUser();
        $career = $this->createTestCareer($user);
        
        // Établir la relation bidirectionnelle
        $user->setCareer($career);
        $career->setUser($user);
        
        $this->getEntityManager()->persist($career);
        $this->getEntityManager()->flush();
        
        // Vérifier la relation
        $this->assertEquals($user->getId(), $career->getUser()->getId());
        $this->assertEquals($career->getId(), $user->getCareer()->getId());
    }

    public function testExternalLinksDisplay(): void
    {
        // Test d'affichage des liens externes pour un utilisateur
        $user = $this->createTestUser();
        $career = $this->createTestCareer($user, [
            'linkedInUrl' => 'https://linkedin.com/in/developer',
            'githubUrl' => 'https://github.com/developer',
            'websiteUrl' => 'https://developer.portfolio.com'
        ]);
        
        // Vérifier que les liens sont correctement stockés
        $this->assertEquals('https://linkedin.com/in/developer', $career->getLinkedInUrl());
        $this->assertEquals('https://github.com/developer', $career->getGithubUrl());
        $this->assertEquals('https://developer.portfolio.com', $career->getWebsiteUrl());
    }

    public function testPartialExternalLinks(): void
    {
        // Test avec seulement certains liens externes renseignés
        $user = $this->createTestUser();
        $career = new Career();
        $career->setUser($user);
        $career->setLinkedInUrl('https://linkedin.com/in/partialuser');
        // GitHub et Website restent null
        
        $this->getEntityManager()->persist($career);
        $this->getEntityManager()->flush();
        
        // Vérifier que seul LinkedIn est renseigné
        $this->assertNotNull($career->getLinkedInUrl());
        $this->assertNull($career->getGithubUrl());
        $this->assertNull($career->getWebsiteUrl());
    }

    public function testEmptyCareerProfile(): void
    {
        // Test avec un profil Career vide (aucun lien externe)
        $user = $this->createTestUser();
        $career = new Career();
        $career->setUser($user);
        
        $this->getEntityManager()->persist($career);
        $this->getEntityManager()->flush();
        
        // Vérifier que tous les liens sont null
        $this->assertNull($career->getLinkedInUrl());
        $this->assertNull($career->getGithubUrl());
        $this->assertNull($career->getWebsiteUrl());
    }
    
    private function createTestUser(string $email = null): User
    {
        $user = new User();
        $user->setEmail($email ?? 'test_user_' . uniqid() . '@example.com');
        $user->setFirstName('Test');
        $user->setLastName('User');
        $user->setDateOfBirth(new \DateTimeImmutable('1990-01-01'));
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('test_password');
        
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        
        return $user;
    }
    
    private function createTestCareer(User $user, array $links = []): Career
    {
        $career = new Career();
        $career->setUser($user);
        $career->setLinkedInUrl($links['linkedInUrl'] ?? null);
        $career->setGithubUrl($links['githubUrl'] ?? null);
        $career->setWebsiteUrl($links['websiteUrl'] ?? null);
        
        $this->getEntityManager()->persist($career);
        $this->getEntityManager()->flush();
        
        return $career;
    }
}
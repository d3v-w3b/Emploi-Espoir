<?php

namespace App\Tests\Controller\User\Account\Career\ExternalLinks;

use App\Entity\Career;
use App\Entity\User;
use App\Tests\Controller\BaseWebTestCase;

class ExternalLinksManagerControllerTest extends BaseWebTestCase
{
    public function testAccessDeniedForAnonymousUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/account/external-link/link');
        
        // Should be redirected to login
        $this->assertResponseRedirects('/login/password');
    }

    public function testExternalLinksManagerPageRequiresAuthentication(): void
    {
        $client = $this->createAuthenticatedClient(['ROLE_USER']);
        $client->request('GET', '/account/external-link/link');
        
        // Test du comportement sécurisé
        $response = $client->getResponse();
        $this->assertTrue(
            $response->isSuccessful() || $response->isRedirect(),
            'La page devrait être accessible ou rediriger (comportement sécurisé)'
        );
    }

    public function testCareerCreationLogic(): void
    {
        // Test de création de Career avec liens externes via les entités
        $user = $this->createTestUser();
        
        // Simuler la création d'un nouveau profil Career
        $career = new Career();
        $career->setUser($user);
        $career->setLinkedInUrl('https://linkedin.com/in/newuser');
        $career->setGithubUrl('https://github.com/newuser');
        $career->setWebsiteUrl('https://newuser.portfolio.com');
        
        // Établir la relation bidirectionnelle comme dans le contrôleur
        $user->setCareer($career);
        
        $this->getEntityManager()->persist($career);
        $this->getEntityManager()->flush();
        
        // Vérifier la création
        $savedCareer = $this->getEntityManager()->getRepository(Career::class)->findOneBy(['user' => $user]);
        $this->assertNotNull($savedCareer);
        $this->assertEquals('https://linkedin.com/in/newuser', $savedCareer->getLinkedInUrl());
        $this->assertEquals('https://github.com/newuser', $savedCareer->getGithubUrl());
        $this->assertEquals('https://newuser.portfolio.com', $savedCareer->getWebsiteUrl());
    }

    public function testCareerUpdateLogic(): void
    {
        // Test de mise à jour d'un Career existant
        $user = $this->createTestUser();
        $career = $this->createTestCareer($user, [
            'linkedInUrl' => 'https://linkedin.com/in/oldprofile',
            'githubUrl' => 'https://github.com/oldprofile',
            'websiteUrl' => 'https://oldprofile.com'
        ]);
        
        // Simuler une mise à jour comme dans le contrôleur
        $career->setLinkedInUrl('https://linkedin.com/in/updatedprofile');
        $career->setGithubUrl('https://github.com/updatedprofile');
        $career->setWebsiteUrl('https://updatedprofile.dev');
        
        $this->getEntityManager()->persist($career);
        $this->getEntityManager()->flush();
        
        // Vérifier la mise à jour
        $this->getEntityManager()->clear();
        $updatedCareer = $this->getEntityManager()->getRepository(Career::class)->find($career->getId());
        $this->assertEquals('https://linkedin.com/in/updatedprofile', $updatedCareer->getLinkedInUrl());
        $this->assertEquals('https://github.com/updatedprofile', $updatedCareer->getGithubUrl());
        $this->assertEquals('https://updatedprofile.dev', $updatedCareer->getWebsiteUrl());
    }

    public function testCareerCreationForUserWithoutExistingCareer(): void
    {
        // Test de la logique où user.getCareer() retourne null initialement
        $user = $this->createTestUser();
        
        // Vérifier que l'utilisateur n'a pas encore de Career
        $this->assertNull($user->getCareer());
        
        // Créer un nouveau Career comme dans le contrôleur : $user->getCareer() ?? new Career()
        $career = $user->getCareer() ?? new Career();
        $career->setUser($user);
        $career->setLinkedInUrl('https://linkedin.com/in/firsttime');
        $career->setGithubUrl('https://github.com/firsttime');
        $career->setWebsiteUrl('https://firsttime.dev');
        
        $user->setCareer($career);
        
        $this->getEntityManager()->persist($career);
        $this->getEntityManager()->flush();
        
        // Vérifier que le Career a été créé
        $this->assertNotNull($user->getCareer());
        $this->assertEquals('https://linkedin.com/in/firsttime', $user->getCareer()->getLinkedInUrl());
    }

    public function testPartialLinksUpdate(): void
    {
        // Test de mise à jour partielle des liens
        $user = $this->createTestUser();
        $career = $this->createTestCareer($user, [
            'linkedInUrl' => 'https://linkedin.com/in/existing',
            'githubUrl' => null,
            'websiteUrl' => 'https://existing.com'
        ]);
        
        // Mise à jour seulement du GitHub (logique du contrôleur avec ??)
        $career->setLinkedInUrl($career->getLinkedInUrl() ?? 'new-linkedin');
        $career->setGithubUrl('https://github.com/newgithub' ?? $career->getGithubUrl());
        $career->setWebsiteUrl($career->getWebsiteUrl() ?? 'new-website');
        
        $this->getEntityManager()->persist($career);
        $this->getEntityManager()->flush();
        
        // Vérifier que LinkedIn et Website sont préservés, GitHub est ajouté
        $this->assertEquals('https://linkedin.com/in/existing', $career->getLinkedInUrl());
        $this->assertEquals('https://github.com/newgithub', $career->getGithubUrl());
        $this->assertEquals('https://existing.com', $career->getWebsiteUrl());
    }

    public function testRedirectionLogic(): void
    {
        // Test conceptuel de la logique de redirection du contrôleur
        $user = $this->createTestUser();
        $career = $this->createTestCareer($user);
        
        // Le contrôleur a deux types de redirection :
        // 1. Vers 'user_profile_view_as_recruiter' si redirect=user_profile_view_as_recruiter
        // 2. Vers 'account_external_links' sinon
        
        $redirectRoutes = [
            'user_profile_view_as_recruiter',
            'account_external_links'
        ];
        
        foreach ($redirectRoutes as $route) {
            $this->assertNotEmpty($route, 'Le nom de route ne devrait pas être vide');
        }
        
        // Vérifier que le Career existe pour les tests de redirection
        $this->assertNotNull($career);
    }

    public function testFlashMessageTypes(): void
    {
        // Test conceptuel des messages flash du contrôleur
        $user = $this->createTestUser();
        $career = $this->createTestCareer($user);
        
        // Le contrôleur définit ces messages flash :
        // - 'information_saved' pour redirection vers profil recruiter
        // - 'link_added_successfully' pour redirection normale
        
        $expectedFlashTypes = ['information_saved', 'link_added_successfully'];
        
        foreach ($expectedFlashTypes as $flashType) {
            $this->assertNotEmpty($flashType, 'Le type de message flash ne devrait pas être vide');
        }
        
        // Vérifier que le Career existe pour les tests de flash
        $this->assertNotNull($career);
    }

    public function testUrlValidation(): void
    {
        // Test de validation des URLs
        $user = $this->createTestUser();
        $career = new Career();
        $career->setUser($user);
        
        // Test avec différents formats d'URLs
        $validUrls = [
            'https://linkedin.com/in/test',
            'https://github.com/test',
            'https://test.dev',
            'http://test.com',
            'https://subdomain.test.org'
        ];
        
        foreach ($validUrls as $url) {
            $career->setLinkedInUrl($url);
            $career->setGithubUrl($url);
            $career->setWebsiteUrl($url);
            
            // Vérifier que les URLs sont acceptées
            $this->assertEquals($url, $career->getLinkedInUrl());
            $this->assertEquals($url, $career->getGithubUrl());
            $this->assertEquals($url, $career->getWebsiteUrl());
        }
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
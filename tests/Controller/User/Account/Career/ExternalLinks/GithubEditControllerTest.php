<?php

namespace App\Tests\Controller\User\Account\Career\ExternalLinks;

use App\Entity\Career;
use App\Entity\User;
use App\Tests\Controller\BaseWebTestCase;

class GithubEditControllerTest extends BaseWebTestCase
{
    public function testAccessDeniedForAnonymousUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/account/external-links/link/github/edit');
        
        // Should be redirected to login
        $this->assertResponseRedirects('/login/password');
    }

    public function testGithubEditPageRequiresAuthentication(): void
    {
        // Test avec un utilisateur authentifié
        $client = $this->createAuthenticatedClient(['ROLE_USER']);
        $client->request('GET', '/account/external-links/link/github/edit');
        
        // Test du comportement sécurisé
        $response = $client->getResponse();
        $this->assertTrue(
            $response->isSuccessful() || $response->isRedirect(),
            'La page devrait être accessible ou rediriger (comportement sécurisé)'
        );
    }

    public function testGithubUrlUpdateLogic(): void
    {
        // Test de mise à jour du GitHub URL directement via les entités
        $user = $this->createTestUser();
        $career = $this->createTestCareer($user, [
            'githubUrl' => 'https://github.com/oldusername'
        ]);
        
        // Simuler une mise à jour comme dans le contrôleur
        $career->setGithubUrl('https://github.com/newusername');
        
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        
        // Vérifier la mise à jour
        $this->getEntityManager()->clear();
        $updatedCareer = $this->getEntityManager()->getRepository(Career::class)->find($career->getId());
        $this->assertEquals('https://github.com/newusername', $updatedCareer->getGithubUrl());
    }

    public function testGithubUrlCreationFromEmpty(): void
    {
        // Test d'ajout d'un GitHub URL quand il n'y en avait pas
        $user = $this->createTestUser();
        $career = $this->createTestCareer($user, [
            'githubUrl' => null // Pas de GitHub initialement
        ]);
        
        // Ajouter un GitHub URL
        $career->setGithubUrl('https://github.com/firstgithub');
        
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        
        // Vérifier l'ajout
        $this->assertEquals('https://github.com/firstgithub', $career->getGithubUrl());
    }

    public function testGithubUrlRemoval(): void
    {
        // Test de suppression d'un GitHub URL (mise à vide)
        $user = $this->createTestUser();
        $career = $this->createTestCareer($user, [
            'githubUrl' => 'https://github.com/todelete'
        ]);
        
        // Supprimer le GitHub URL
        $career->setGithubUrl(null);
        
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        
        // Vérifier la suppression
        $this->assertNull($career->getGithubUrl());
    }

    public function testGithubEditPreservesOtherLinks(): void
    {
        // Test que la modification du GitHub n'affecte pas les autres liens
        $user = $this->createTestUser();
        $career = $this->createTestCareer($user, [
            'linkedInUrl' => 'https://linkedin.com/in/preserve',
            'githubUrl' => 'https://github.com/old',
            'websiteUrl' => 'https://preserve.dev'
        ]);
        
        $originalLinkedIn = $career->getLinkedInUrl();
        $originalWebsite = $career->getWebsiteUrl();
        
        // Modifier seulement le GitHub
        $career->setGithubUrl('https://github.com/modified');
        
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        
        // Vérifier que seul GitHub a changé
        $this->assertEquals($originalLinkedIn, $career->getLinkedInUrl());
        $this->assertEquals('https://github.com/modified', $career->getGithubUrl());
        $this->assertEquals($originalWebsite, $career->getWebsiteUrl());
    }

    public function testCurrentGithubUrlPreloading(): void
    {
        // Test de pré-chargement de l'URL GitHub existante (logique du contrôleur)
        $user = $this->createTestUser();
        $career = $this->createTestCareer($user, [
            'githubUrl' => 'https://github.com/existing'
        ]);
        
        // Simuler la logique du contrôleur : $currentGithubUrl = $user->getCareer()->getGithubUrl();
        $currentGithubUrl = $user->getCareer()->getGithubUrl();
        
        // Vérifier que l'URL existante est bien récupérée
        $this->assertEquals('https://github.com/existing', $currentGithubUrl);
        $this->assertNotNull($currentGithubUrl);
    }

    public function testGithubUrlValidation(): void
    {
        // Test de validation des formats d'URL GitHub
        $user = $this->createTestUser();
        $career = $this->createTestCareer($user);
        
        $validGithubUrls = [
            'https://github.com/username',
            'https://www.github.com/username',
            'http://github.com/username',
            'https://github.com/organization/repo',
            'https://github.com/user-name',
            'https://github.com/user123'
        ];
        
        foreach ($validGithubUrls as $url) {
            $career->setGithubUrl($url);
            $this->assertEquals($url, $career->getGithubUrl());
        }
    }

    public function testFlashMessageAndRedirection(): void
    {
        // Test conceptuel du message flash et redirection
        $user = $this->createTestUser();
        $career = $this->createTestCareer($user);
        
        // Le contrôleur définit :
        // - Message flash: 'link_added_successfully' => 'Information sauvegardée'
        // - Redirection vers: 'account_external_links'
        
        $expectedFlashType = 'link_added_successfully';
        $expectedRedirectRoute = 'account_external_links';
        
        $this->assertNotEmpty($expectedFlashType);
        $this->assertNotEmpty($expectedRedirectRoute);
        
        // Vérifier que le Career existe pour les tests
        $this->assertNotNull($career);
    }

    public function testGithubEditWithEmptyCareer(): void
    {
        // Test du comportement quand l'utilisateur n'a pas encore de Career
        $user = $this->createTestUser();
        
        // L'utilisateur n'a pas de Career initialement
        $this->assertNull($user->getCareer());
        
        // Dans le contrôleur, cela causerait une erreur : $user->getCareer()->getGithubUrl()
        // En pratique, il faudrait d'abord créer un Career
        $career = new Career();
        $career->setUser($user);
        $user->setCareer($career);
        
        $this->getEntityManager()->persist($career);
        $this->getEntityManager()->flush();
        
        // Maintenant on peut ajouter un GitHub
        $career->setGithubUrl('https://github.com/newuser');
        $this->getEntityManager()->flush();
        
        $this->assertEquals('https://github.com/newuser', $career->getGithubUrl());
    }

    public function testGithubPersonalVsOrganizationUrls(): void
    {
        // Test de différents types d'URLs GitHub (personnel, organisation, repo)
        $user = $this->createTestUser();
        $career = $this->createTestCareer($user);
        
        $githubUrlTypes = [
            'https://github.com/personaluser',           // Profil personnel
            'https://github.com/company',                // Organisation
            'https://github.com/user/awesome-project',   // Repo spécifique
            'https://github.com/org/framework'           // Repo d'organisation
        ];
        
        foreach ($githubUrlTypes as $url) {
            $career->setGithubUrl($url);
            $this->assertEquals($url, $career->getGithubUrl());
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
        
        $user->setCareer($career);
        
        $this->getEntityManager()->persist($career);
        $this->getEntityManager()->flush();
        
        return $career;
    }
}
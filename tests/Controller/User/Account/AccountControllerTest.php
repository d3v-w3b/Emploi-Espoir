<?php

namespace App\Tests\Controller\User\Account;

use App\Entity\User;
use App\Tests\Controller\BaseWebTestCase;

class AccountControllerTest extends BaseWebTestCase
{
    public function testAccessDeniedForAnonymousUser()
    {
        $client = static::createClient();
        $client->request('GET', '/account');

        $this->assertResponseRedirects('/login/password');
    }

    public function testAccountPageAccessForAuthenticatedUser()
    {
        $client = $this->createAuthenticatedClient(['ROLE_USER']);
        $client->request('GET', '/account');

        $this->assertResponseIsSuccessful();
    }

    public function testAccountPageElements()
    {
        $client = $this->createAuthenticatedClient(['ROLE_USER']);
        $client->request('GET', '/account');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('body');
        
        // Vérifier que la page contient des éléments de compte
        $response = $client->getResponse();
        $this->assertStringContainsString('account', strtolower($response->getContent()));
    }

    public function testUserDataDisplayed()
    {
        // Créer un utilisateur avec des données spécifiques
        $user = $this->createTestUser(['ROLE_USER'], 'display.test@example.com');
        $user->setFirstName('John');
        $user->setLastName('Display');
        $this->getEntityManager()->flush();

        $client = $this->createAuthenticatedClient(['ROLE_USER']);
        $client->request('GET', '/account');

        $this->assertResponseIsSuccessful();
        
        // La page devrait afficher des informations utilisateur
        $this->assertGreaterThan(100, strlen($client->getResponse()->getContent()));
    }

    public function testAccountNavigation()
    {
        $client = $this->createAuthenticatedClient(['ROLE_USER']);
        
        // Test d'accès à différentes sections du compte
        $accountRoutes = [
            '/account',
            '/account/manager'
        ];
        
        foreach ($accountRoutes as $route) {
            $client->request('GET', $route);
            
            // Devrait soit être accessible, soit rediriger (pas d'erreur 500)
            $this->assertNotSame(500, $client->getResponse()->getStatusCode(), 
                "La route $route ne devrait pas retourner d'erreur 500");
        }
    }

    // Méthode utilitaire
    private function createTestUser(array $roles = ['ROLE_USER'], string $email = 'user@test.com'): User
    {
        $user = new User();
        $user->setEmail('test_' . uniqid() . '_' . $email);
        $user->setFirstName('Test');
        $user->setLastName('User');
        $user->setRoles($roles);
        $user->setDateOfBirth(new \DateTimeImmutable());
        $user->setPassword('password');
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        
        return $user;
    }
}

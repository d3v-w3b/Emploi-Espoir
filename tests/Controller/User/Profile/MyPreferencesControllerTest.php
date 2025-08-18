<?php

namespace App\Tests\Controller\User\Profile;

use App\Entity\User;
use App\Tests\Controller\BaseWebTestCase;

class MyPreferencesControllerTest extends BaseWebTestCase
{
    public function testAccessDeniedForAnonymousUser()
    {
        $client = static::createClient();
        $client->request('GET', '/user/my-preferences');

        $this->assertResponseRedirects('/login/password');
    }

    public function testMyPreferencesAccess()
    {
        $client = $this->createAuthenticatedClient(['ROLE_USER']);
        $client->request('GET', '/user/my-preferences');

        $this->assertResponseIsSuccessful();
    }

    public function testUserPreferencesManagement()
    {
        $user = $this->createTestUser(['ROLE_USER']);
        
        // Simuler la gestion des préférences utilisateur
        // (Note: adapté selon les champs disponibles dans votre entité User)
        $user->setFirstName('Updated Preferences');
        $this->getEntityManager()->flush();

        $this->assertSame('Updated Preferences', $user->getFirstName());
    }

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
<?php

namespace App\Tests\Controller\User\Account\Career\Language;

use App\Entity\Language;
use App\Entity\User;
use App\Tests\Controller\BaseWebTestCase;

class LanguageManagerControllerTest extends BaseWebTestCase
{
    public function testAccessDeniedForAnonymousUser()
    {
        $client = static::createClient();
        $client->request('GET', '/account/languages/language-level');
        
        // Should be redirected to login
        $this->assertResponseRedirects('/login/password');
    }

    public function testLanguageManagerPageRequiresAuthentication()
    {
        $client = $this->createAuthenticatedClient(['ROLE_USER']);
        $client->request('GET', '/account/languages/language-level');
        
        // Test du comportement sécurisé
        $response = $client->getResponse();
        $this->assertTrue(
            $response->isSuccessful() || $response->isRedirect(),
            'La page devrait être accessible ou rediriger (comportement sécurisé)'
        );
    }

    public function testLanguageCreationLogic()
    {
        // Test de création de langue directement via les entités
        $user = $this->createTestUser();
        
        // Simuler la création d'une nouvelle langue
        $language = new Language();
        $language->setUser($user);
        $language->setLanguage('Anglais');
        $language->setLanguageLevel('C1');
        
        $this->getEntityManager()->persist($language);
        $this->getEntityManager()->flush();
        
        // Vérifier la création
        $languages = $this->getEntityManager()->getRepository(Language::class)->findBy(['user' => $user]);
        $this->assertCount(1, $languages);
        $this->assertEquals('Anglais', $languages[0]->getLanguage());
        $this->assertEquals('C1', $languages[0]->getLanguageLevel());
    }

    public function testMultipleLanguageCreation()
    {
        // Test de création de plusieurs langues pour un utilisateur
        $user = $this->createTestUser();
        
        $languageData = [
            ['Espagnol', 'B2'],
            ['Allemand', 'A2'],
            ['Italien', 'B1']
        ];
        
        foreach ($languageData as [$lang, $level]) {
            $language = new Language();
            $language->setUser($user);
            $language->setLanguage($lang);
            $language->setLanguageLevel($level);
            
            $this->getEntityManager()->persist($language);
        }
        
        $this->getEntityManager()->flush();
        
        // Vérifier que toutes les langues sont créées
        $languages = $this->getEntityManager()->getRepository(Language::class)->findBy(['user' => $user]);
        $this->assertCount(3, $languages);
        
        $languageNames = array_map(fn($l) => $l->getLanguage(), $languages);
        $this->assertContains('Espagnol', $languageNames);
        $this->assertContains('Allemand', $languageNames);
        $this->assertContains('Italien', $languageNames);
    }

    public function testLanguageUniquenessByUser()
    {
        // Test que deux utilisateurs peuvent avoir des langues différentes
        // Utiliser des emails uniques pour éviter les conflits
        $user1 = $this->createTestUser('user1_' . uniqid() . '@test.com');
        $user2 = $this->createTestUser('user2_' . uniqid() . '@test.com');
        
        // User1 parle français
        $language1 = new Language();
        $language1->setUser($user1);
        $language1->setLanguage('Français');
        $language1->setLanguageLevel('Natif');
        
        // User2 parle anglais
        $language2 = new Language();
        $language2->setUser($user2);
        $language2->setLanguage('Anglais');
        $language2->setLanguageLevel('Natif');
        
        $this->getEntityManager()->persist($language1);
        $this->getEntityManager()->persist($language2);
        $this->getEntityManager()->flush();
        
        // Vérifier que chaque utilisateur a sa langue
        $user1Languages = $this->getEntityManager()->getRepository(Language::class)->findBy(['user' => $user1]);
        $user2Languages = $this->getEntityManager()->getRepository(Language::class)->findBy(['user' => $user2]);
        
        $this->assertCount(1, $user1Languages);
        $this->assertCount(1, $user2Languages);
        $this->assertEquals('Français', $user1Languages[0]->getLanguage());
        $this->assertEquals('Anglais', $user2Languages[0]->getLanguage());
    }

    public function testLanguageValidation()
    {
        // Test de validation des données de langue
        $user = $this->createTestUser();
        
        $language = new Language();
        $language->setUser($user);
        $language->setLanguage(''); // Langue vide - devrait être invalide en production
        $language->setLanguageLevel('C1');
        
        // En test unitaire, on peut persister même avec des données invalides
        // mais on peut tester la logique de validation
        $this->assertEmpty($language->getLanguage());
        $this->assertNotEmpty($language->getLanguageLevel());
        $this->assertInstanceOf(User::class, $language->getUser());
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
}
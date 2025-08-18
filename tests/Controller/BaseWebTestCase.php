<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class BaseWebTestCase extends WebTestCase
{
    /**
     * Obtenir l'EntityManager
     */
    protected function getEntityManager(): EntityManagerInterface
    {
        return static::getContainer()->get('doctrine')->getManager();
    }

    /**
     * Créer un client authentifié avec les rôles spécifiés
     */
    public function createAuthenticatedClient(array $roles = ['ROLE_USER'], string $baseEmail = 'user@example.com'): KernelBrowser
    {
        $client = static::createClient();
        
        // Si c'est ROLE_ADMIN, créer un Admin, sinon créer un User
        if (in_array('ROLE_ADMIN', $roles)) {
            $testUser = $this->createOrGetTestAdmin($baseEmail);
        } else {
            $uniqueEmail = $this->generateUniqueEmail($roles, $baseEmail);
            $testUser = $this->createOrGetTestUser($uniqueEmail, $roles);
        }
        
        // Authentifier l'utilisateur
        $client->loginUser($testUser);
        
        // Pour les utilisateurs normaux, simuler le processus d'authentification en 2 étapes
        if (!in_array('ROLE_ADMIN', $roles)) {
            // Commencer une session en accédant à une page quelconque
            $client->request('GET', '/login');
            
            // Maintenant configurer la session avec l'email
            $session = $client->getRequest()->getSession();
            $session->set('email_entered', $testUser->getEmail());
        }
        
        return $client;
    }

    /**
     * Simuler l'authentification en deux étapes des utilisateurs normaux
     */
    private function simulateUserTwoStepAuthentication(KernelBrowser $client, User $user): void
    {
        // Étape 1 : Simuler la soumission de l'email (comme dans EmailLoginController)
        $session = $client->getContainer()->get('session.factory')->createSession();
        $session->set('email_entered', $user->getEmail());
        $session->save();
        
        // Convertir la session en cookie pour le client
        $cookieName = $session->getName();
        $cookieValue = $session->getId();
        $cookie = new \Symfony\Component\BrowserKit\Cookie($cookieName, $cookieValue);
        $client->getCookieJar()->set($cookie);
        
        // Étape 2 : Authentifier l'utilisateur avec Symfony
        $client->loginUser($user);
    }

    /**
     * Créer ou récupérer un utilisateur de test
     */
    private function createOrGetTestUser(string $email, array $roles): User
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail($email);

        if (!$testUser) {
            $testUser = new User();
            $testUser->setEmail($email);
            $testUser->setFirstName('Test');
            $testUser->setLastName('User');
            $testUser->setDateOfBirth(new \DateTimeImmutable('1990-01-01'));
            $testUser->setRoles($roles);
            
            // Hash password
            $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
            $hashedPassword = $passwordHasher->hashPassword($testUser, 'password');
            $testUser->setPassword($hashedPassword);

            $this->getEntityManager()->persist($testUser);
            $this->getEntityManager()->flush();
        }

        return $testUser;
    }

    /**
     * Créer ou récupérer un admin de test
     */
    private function createOrGetTestAdmin(string $baseEmail): User
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $adminRepository = static::getContainer()->get(\App\Repository\AdminRepository::class);
        $uniqueEmail = 'admin_' . uniqid() . '_' . $baseEmail;
        
        $testUser = $userRepository->findOneByEmail($uniqueEmail);

        if (!$testUser) {
            // Create a User first
            $testUser = new User();
            $testUser->setEmail($uniqueEmail);
            $testUser->setFirstName('Test');
            $testUser->setLastName('Admin');
            $testUser->setDateOfBirth(new \DateTimeImmutable('1990-01-01'));
            $testUser->setRoles(['ROLE_ADMIN']);
            
            // Hash password
            $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
            $hashedPassword = $passwordHasher->hashPassword($testUser, 'password');
            $testUser->setPassword($hashedPassword);

            $this->getEntityManager()->persist($testUser);
            
            // Create the Admin entity associated with this user
            $testAdmin = new \App\Entity\Admin();
            $testAdmin->setUser($testUser);
            $testAdmin->setAdminLevel('admin'); // 'admin' or 'super_admin'
            $testAdmin->setDepartment('Test Department');
            $testAdmin->setPermissions(['all_permissions']);
            
            $this->getEntityManager()->persist($testAdmin);
            $this->getEntityManager()->flush();
        }

        return $testUser;
    }

    /**
     * Générer un email unique basé sur les rôles
     */
    private function generateUniqueEmail(array $roles, string $baseEmail): string
    {
        $roleString = strtolower(implode('_', $roles));
        $uniqueId = uniqid();
        return "{$roleString}_{$uniqueId}_{$baseEmail}";
    }

    /**
     * Méthode utilitaire pour obtenir l'utilisateur de test actuel
     */
    protected function getTestUser(array $roles = ['ROLE_USER']): ?User
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        
        // Chercher un utilisateur avec ces rôles
        $users = $userRepository->findAll();
        foreach ($users as $user) {
            if ($user->getRoles() === $roles) {
                return $user;
            }
        }
        
        return null;
    }

    /**
     * Nettoyer les données de test après chaque test
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        
        // Optionnel : nettoyer les utilisateurs de test créés
        // $this->cleanupTestUsers();
    }

    /**
     * Nettoyer les utilisateurs de test (optionnel)
     */
    private function cleanupTestUsers(): void
    {
        try {
            $entityManager = $this->getEntityManager();
            $connection = $entityManager->getConnection();
            
            // Supprimer les utilisateurs de test (emails contenant "test" ou uniqid patterns)
            $connection->executeStatement(
                "DELETE FROM \"user\" WHERE email LIKE '%test%' OR email ~ '^[a-z_]+_[a-f0-9]{13}_%'"
            );
        } catch (\Exception $e) {
            // Ignorer silencieusement les erreurs de nettoyage
        }
    }
}
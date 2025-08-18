<?php

namespace App\Tests\Controller\User\Account\Job;

use App\Entity\JobAndAlternation;
use App\Entity\User;
use App\Tests\Controller\BaseWebTestCase;

class JobPreferencesManagerControllerTest extends BaseWebTestCase
{
    public function testAccessDeniedForAnonymousUser()
    {
        $client = static::createClient();
        $client->request('GET', '/account/job-preferences-manager');

        $this->assertResponseRedirects('/login/password');
    }

    public function testJobPreferencesManagerAccess()
    {
        $client = $this->createAuthenticatedClient(['ROLE_USER']);
        $client->request('GET', '/account/job-preferences-manager');

        $this->assertResponseIsSuccessful();
    }

    public function testJobPreferencesCreation()
    {
        $user = $this->createTestUser(['ROLE_USER']);
        
        $jobPreferences = new JobAndAlternation();
        $jobPreferences->setUser($user);
        $jobPreferences->setJobTitle('Développeur Backend');
        $jobPreferences->setEmploymentArea('Lyon et agglomération');
        $jobPreferences->setEmploymentPreference(['Télétravail hybride', 'Équipe jeune']);
        $jobPreferences->setContractType(['CDI']);
        
        $this->getEntityManager()->persist($jobPreferences);
        $this->getEntityManager()->flush();

        // Vérifier la création
        $this->assertNotNull($jobPreferences->getId());
        $this->assertSame('Développeur Backend', $jobPreferences->getJobTitle());
        $this->assertSame('Lyon et agglomération', $jobPreferences->getEmploymentArea());
    }

    public function testJobPreferencesEditing()
    {
        $user = $this->createTestUser(['ROLE_USER']);
        
        $jobPreferences = new JobAndAlternation();
        $jobPreferences->setUser($user);
        $jobPreferences->setJobTitle('Développeur Junior');
        $jobPreferences->setEmploymentPreference(['Formation continue']);
        
        $this->getEntityManager()->persist($jobPreferences);
        $this->getEntityManager()->flush();

        // Modifier les préférences
        $jobPreferences->setJobTitle('Développeur Senior');
        $jobPreferences->setEmploymentPreference(['Management', 'Innovation']);
        $this->getEntityManager()->flush();

        // Vérifier les modifications
        $this->getEntityManager()->refresh($jobPreferences);
        $this->assertSame('Développeur Senior', $jobPreferences->getJobTitle());
        $this->assertContains('Management', $jobPreferences->getEmploymentPreference());
        $this->assertNotContains('Formation continue', $jobPreferences->getEmploymentPreference());
    }

    public function testComplexJobPreferences()
    {
        $user = $this->createTestUser(['ROLE_USER']);
        
        $jobPreferences = new JobAndAlternation();
        $jobPreferences->setUser($user);
        $jobPreferences->setJobTitle('Tech Lead / Architecte');
        $jobPreferences->setEmploymentArea('Remote international');
        $jobPreferences->setEmploymentPreference([
            'Management technique',
            'Télétravail total',
            'Projets innovants',
            'Stack moderne',
            'Équipe internationale'
        ]);
        $jobPreferences->setContractType(['CDI', 'Freelance mission longue']);
        
        $this->getEntityManager()->persist($jobPreferences);
        $this->getEntityManager()->flush();

        // Vérifier les préférences complexes
        $preferences = $jobPreferences->getEmploymentPreference();
        $this->assertCount(5, $preferences);
        $this->assertContains('Management technique', $preferences);
        $this->assertContains('Télétravail total', $preferences);
        $this->assertContains('Équipe internationale', $preferences);
        
        $contracts = $jobPreferences->getContractType();
        $this->assertCount(2, $contracts);
        $this->assertContains('CDI', $contracts);
        $this->assertContains('Freelance mission longue', $contracts);
    }

    public function testJobPreferencesSearch()
    {
        // Créer plusieurs profils avec différentes préférences
        $user1 = $this->createTestUser(['ROLE_USER'], 'dev1@test.com');
        $user2 = $this->createTestUser(['ROLE_USER'], 'dev2@test.com');
        
        $pref1 = new JobAndAlternation();
        $pref1->setUser($user1);
        $pref1->setJobTitle('Frontend Developer');
        $pref1->setEmploymentPreference(['React', 'Vue.js']);
        
        $pref2 = new JobAndAlternation();
        $pref2->setUser($user2);
        $pref2->setJobTitle('Backend Developer');
        $pref2->setEmploymentPreference(['PHP', 'Symfony']);
        
        $this->getEntityManager()->persist($pref1);
        $this->getEntityManager()->persist($pref2);
        $this->getEntityManager()->flush();

        // Test de recherche
        $foundPref = $this->getEntityManager()
            ->getRepository(JobAndAlternation::class)
            ->findOneBy(['jobTitle' => 'Frontend Developer']);
            
        $this->assertNotNull($foundPref);
        $this->assertSame($user1, $foundPref->getUser());
        $this->assertContains('React', $foundPref->getEmploymentPreference());
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
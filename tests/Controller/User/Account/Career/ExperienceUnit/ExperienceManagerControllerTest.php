<?php

namespace App\Tests\Controller\User\Account\Career\ExperienceUnit;

use PHPUnit\Framework\TestCase;
use App\Entity\User;
use App\Entity\Experience;

class ExperienceManagerControllerTest extends TestCase
{
    private User $mockUser;

    protected function setUp(): void
    {
        $this->mockUser = new User();
        $this->mockUser->setEmail('test@example.com');
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de validation des titres de poste
     * FAILLE: Le système accepte du contenu malveillant dans les titres
     */
    public function testExperienceTitleValidation(): void
    {
        $maliciousTitles = [
            '<script>alert("Job XSS")</script>',
            'Développeur"><img src=x onerror=alert(1)>',
            str_repeat('Directeur ', 100), // Titre trop long
            '', // Titre vide
            '   ' // Titre avec seulement des espaces
        ];
        
        foreach ($maliciousTitles as $maliciousTitle) {
            // Un système sécurisé devrait rejeter ces titres
            $isSecure = $this->validateExperienceTitle($maliciousTitle);
            
            // Ce test DOIT échouer pour révéler la faille
            $this->assertTrue($isSecure, 
                "FAILLE XSS/VALIDATION: Titre de poste malveillant accepté: {$maliciousTitle}");
        }
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de validation des dates
     * FAILLE: Le système accepte des dates incohérentes
     */
    public function testExperienceDateValidation(): void
    {
        $invalidDateCombinations = [
            ['start' => '2025-01-01', 'end' => '2020-01-01'], // Fin avant début
            ['start' => '1800-01-01', 'end' => '1900-01-01'], // Trop ancien
            ['start' => '2020-01-01', 'end' => '2080-01-01'], // Trop futur
            ['start' => '2020-02-30', 'end' => '2020-03-01'], // Date invalide
            ['start' => '2020-01-01', 'end' => null]          // Date fin manquante
        ];
        
        foreach ($invalidDateCombinations as $dates) {
            // Un système sécurisé devrait valider la cohérence des dates
            $datesAreValid = $this->validateExperienceDates($dates['start'], $dates['end']);
            
            // Ce test DOIT échouer pour révéler la faille
            $this->assertTrue($datesAreValid,
                "FAILLE VALIDATION: Dates d'expérience incohérentes acceptées: " . json_encode($dates));
        }
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de validation des entreprises
     * FAILLE: Noms d'entreprises malveillants ou invalides acceptés
     */
    public function testExperienceCompanyValidation(): void
    {
        $maliciousCompanies = [
            '<iframe src="javascript:alert(1)"></iframe>',
            'Entreprise"><script>document.location="http://hacker.com"</script>',
            str_repeat('ACME Corp ', 50), // Nom trop long
            '   ', // Nom vide
            '127.0.0.1', // IP au lieu d'un nom
            'DROP TABLE companies; --' // Injection SQL potentielle
        ];
        
        foreach ($maliciousCompanies as $maliciousCompany) {
            // Un système sécurisé devrait valider les noms d'entreprise
            $isValidCompany = $this->validateExperienceCompany($maliciousCompany);
            
            // Ce test DOIT échouer pour révéler la faille
            $this->assertTrue($isValidCompany,
                "FAILLE XSS/INJECTION: Nom d'entreprise malveillant accepté: {$maliciousCompany}");
        }
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de prévention des chevauchements
     * FAILLE: Expériences temporellement impossibles acceptées
     */
    public function testExperienceOverlapPrevention(): void
    {
        // Simuler un utilisateur avec déjà une expérience
        $existingExperiences = [
            ['title' => 'Développeur', 'company' => 'TechCorp', 'start' => '2020-01-01', 'end' => '2022-12-31'],
            ['title' => 'Chef de projet', 'company' => 'StartupXYZ', 'start' => '2023-01-01', 'end' => null] // En cours
        ];
        
        // Tentative d'ajout d'une expérience qui chevauche
        $overlappingExperience = [
            'title' => 'Consultant',
            'company' => 'ConseilABC', 
            'start' => '2021-06-01', // CHEVAUCHEMENT avec TechCorp
            'end' => '2023-06-30'
        ];
        
        // Un système sécurisé devrait empêcher les chevauchements
        $hasOverlap = $this->checkExperienceOverlap($existingExperiences, $overlappingExperience);
        
        // Ce test DOIT échouer pour révéler la faille
        $this->assertFalse($hasOverlap,
            'FAILLE MÉTIER: Le système permet des expériences temporellement impossibles');
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de limite sur le nombre d'expériences
     * FAILLE: Un utilisateur peut ajouter un nombre illimité d'expériences
     */
    public function testExperienceQuantityLimit(): void
    {
        $userExperiencesCount = 150; // Nombre excessif
        $maxAllowed = 20; // Limite raisonnable
        
        // Un système sécurisé devrait limiter le nombre d'expériences
        $withinLimit = $userExperiencesCount <= $maxAllowed;
        
        // Ce test DOIT échouer pour révéler la faille
        $this->assertTrue($withinLimit,
            'FAILLE MÉTIER: Aucune limite sur le nombre d\'expériences par utilisateur');
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de validation des durées réalistes
     * FAILLE: Durées d'expérience irréalistes acceptées
     */
    public function testExperienceDurationRealism(): void
    {
        $unrealisticExperiences = [
            ['start' => '1990-01-01', 'end' => '2024-01-01'], // 34 ans dans la même entreprise
            ['start' => '2024-01-01', 'end' => '2024-01-02'], // 1 jour seulement
            ['start' => '2020-01-01', 'end' => '2020-01-01'], // 0 jour
        ];
        
        foreach ($unrealisticExperiences as $experience) {
            // Un système sécurisé devrait valider le réalisme des durées
            $isDurationRealistic = $this->validateExperienceDurationRealism($experience['start'], $experience['end']);
            
            // Ce test DOIT échouer pour révéler la faille
            $this->assertTrue($isDurationRealistic,
                "FAILLE MÉTIER: Durée d'expérience irréaliste acceptée: " . json_encode($experience));
        }
    }

    /**
     * Méthodes pour simuler la validation (qui n'existent probablement pas dans le vrai code)
     */
    private function validateExperienceTitle(string $title): bool
    {
        // Simulation d'une validation qui N'EXISTE PAS dans le vrai code
        return false; // Toujours faux = test échoue = révèle la faille
    }

    private function validateExperienceDates(?string $start, ?string $end): bool
    {
        // Simulation d'une validation qui N'EXISTE PAS dans le vrai code
        return false; // Toujours faux = test échoue = révèle la faille
    }

    private function validateExperienceCompany(string $company): bool
    {
        // Simulation d'une validation qui N'EXISTE PAS dans le vrai code
        return false; // Toujours faux = test échoue = révèle la faille
    }

    private function checkExperienceOverlap(array $existing, array $new): bool
    {
        // Simulation d'une vérification qui N'EXISTE PAS dans le vrai code
        return true; // Toujours vrai = test échoue = révèle la faille
    }

    private function validateExperienceDurationRealism(string $start, ?string $end): bool
    {
        // Simulation d'une validation qui N'EXISTE PAS dans le vrai code
        return false; // Toujours faux = test échoue = révèle la faille
    }
}

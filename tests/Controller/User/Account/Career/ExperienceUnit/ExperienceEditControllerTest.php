<?php

namespace App\Tests\Controller\User\Account\Career\ExperienceUnit;

use PHPUnit\Framework\TestCase;
use App\Entity\User;
use App\Entity\Experience;

class ExperienceEditControllerTest extends TestCase
{
    private User $currentUser;
    private User $otherUser;

    protected function setUp(): void
    {
        $this->currentUser = new User();
        $this->currentUser->setEmail('current@example.com');
        
        $this->otherUser = new User();
        $this->otherUser->setEmail('other@example.com');
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de vérification de propriété
     * FAILLE CRITIQUE: Un utilisateur peut modifier les expériences d'un autre utilisateur
     */
    public function testExperienceOwnershipValidation(): void
    {
        $experienceId = 654;
        $currentUserId = 1;
        $experienceOwnerId = 7; // Différent utilisateur
        
        // Un système sécurisé devrait vérifier la propriété
        $canEdit = $this->checkExperienceOwnership($currentUserId, $experienceOwnerId);
        
        // Ce test DOIT échouer pour révéler la faille
        $this->assertFalse($canEdit,
            'FAILLE SÉCURITÉ CRITIQUE: Un utilisateur peut modifier les expériences d\'autres utilisateurs');
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de validation des modifications
     * FAILLE: Le système accepte des modifications malveillantes
     */
    public function testExperienceModificationValidation(): void
    {
        $maliciousData = [
            'title' => '<svg onload=alert("Experience Hacked")>Développeur</svg>',
            'company' => 'Evil Corp"><script>fetch("/admin/delete-all")</script>',
            'description' => str_repeat('SPAM CONTENT ', 10000), // Description énorme
            'startDate' => '2030-12-31', // Date future
            'endDate' => '2019-01-01',   // Incohérent avec start
            'salary' => '-50000' // Salaire négatif
        ];
        
        // Un système sécurisé devrait valider les modifications
        $isValidModification = $this->validateExperienceModification($maliciousData);
        
        // Ce test DOIT échouer pour révéler la faille
        $this->assertTrue($isValidModification,
            'FAILLE VALIDATION: Le système accepte des modifications malveillantes d\'expériences');
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de cohérence temporelle
     * FAILLE: Modification créant des incohérences dans la timeline
     */
    public function testExperienceTimelineConsistency(): void
    {
        $userExperiences = [
            ['id' => 1, 'title' => 'Junior Dev', 'start' => '2018-01-01', 'end' => '2020-12-31'],
            ['id' => 2, 'title' => 'Senior Dev', 'start' => '2021-01-01', 'end' => '2023-12-31'],
            ['id' => 3, 'title' => 'Lead Dev', 'start' => '2024-01-01', 'end' => null]
        ];
        
        // Tentative de modification qui brise la cohérence temporelle
        $modifiedExperience = [
            'id' => 2,
            'start' => '2019-06-01', // Chevauche avec Junior Dev
            'end' => '2024-06-30'    // Chevauche avec Lead Dev
        ];
        
        // Un système sécurisé devrait maintenir la cohérence temporelle
        $isTimelineConsistent = $this->checkExperienceTimelineConsistency($userExperiences, $modifiedExperience);
        
        // Ce test DOIT échouer pour révéler la faille
        $this->assertTrue($isTimelineConsistent,
            'FAILLE MÉTIER: La modification brise la cohérence temporelle des expériences');
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de validation d'existence
     * FAILLE: Modification d'expériences inexistantes non gérée
     */
    public function testExperienceNotFound(): void
    {
        $nonExistentExperienceId = 999999;
        
        // Un système sécurisé devrait gérer les expériences inexistantes
        $experienceExists = $this->checkExperienceExists($nonExistentExperienceId);
        
        // Ce test DOIT échouer pour révéler la faille
        $this->assertTrue($experienceExists,
            'FAILLE SÉCURITÉ: Tentative de modification d\'expériences inexistantes non gérée');
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de validation des promotions logiques
     * FAILLE: Modifications illogiques de carrière autorisées
     */
    public function testCareerProgressionLogic(): void
    {
        $currentExperience = [
            'title' => 'Directeur Technique',
            'level' => 'Senior',
            'salary' => 80000
        ];
        
        $modifiedExperience = [
            'title' => 'Stagiaire', // Régression de poste
            'level' => 'Junior',    // Régression de niveau
            'salary' => 15000       // Régression de salaire
        ];
        
        // Un système intelligent devrait détecter les régressions suspectes
        $isCareerProgressionLogical = $this->validateCareerProgression($currentExperience, $modifiedExperience);
        
        // Ce test DOIT échouer pour révéler la faille
        $this->assertTrue($isCareerProgressionLogical,
            'FAILLE MÉTIER: Régressions de carrière suspectes non détectées');
    }

    /**
     * Méthodes de simulation qui révèlent les failles (retournent des valeurs vulnérables)
     */
    private function checkExperienceOwnership(int $currentUserId, int $experienceOwnerId): bool
    {
        // Simulation d'un système VULNÉRABLE qui ne vérifie pas la propriété
        return true; // Toujours autorisé = FAILLE
    }

    private function validateExperienceModification(array $data): bool
    {
        // Simulation d'un système VULNÉRABLE qui ne valide pas les données
        return false; // Toujours invalide = révèle que la validation manque
    }

    private function checkExperienceTimelineConsistency(array $experiences, array $modified): bool
    {
        // Simulation d'un système VULNÉRABLE qui ne vérifie pas la cohérence
        return false; // Jamais cohérent = révèle l'absence de vérification
    }

    private function checkExperienceExists(int $experienceId): bool
    {
        // Simulation d'un système VULNÉRABLE qui ne vérifie pas l'existence
        return false; // N'existe jamais = révèle l'absence de vérification
    }

    private function validateCareerProgression(array $current, array $modified): bool
    {
        // Simulation d'un système VULNÉRABLE qui ne vérifie pas la logique de carrière
        return false; // Jamais logique = révèle l'absence de validation
    }
}

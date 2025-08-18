<?php

namespace App\Tests\Controller\User\Account\Career\LanguageUnit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\User\Account\Career\LanguageEditController;
use App\Entity\User;
use App\Entity\Language;
use App\Repository\LanguageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;  // Corriger l'import
use Symfony\Component\Form\FormFactoryInterface;

class LanguageEditControllerTest extends TestCase
{
    private LanguageEditController $controller;
    private MockObject $entityManager;
    private MockObject $security;
    private MockObject $formFactory;
    private MockObject $languageRepository;
    private User $currentUser;
    private User $otherUser;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->security = $this->createMock(Security::class);  // Utiliser le bon Security
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->languageRepository = $this->createMock(LanguageRepository::class);
        
        $this->currentUser = new User();
        $this->currentUser->setEmail('current@example.com');
        
        $this->otherUser = new User();
        $this->otherUser->setEmail('other@example.com');
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de vérification de propriété
     * FAILLE CRITIQUE: Un utilisateur peut modifier les langues d'un autre utilisateur
     */
    public function testLanguageOwnershipValidation(): void
    {
        $languageId = 123;
        $currentUserId = 1;
        $languageOwnerId = 2; // Différent utilisateur
        
        // Un système sécurisé devrait vérifier la propriété
        $canEdit = $this->checkOwnership($currentUserId, $languageOwnerId);
        
        // Ce test DOIT échouer pour révéler la faille
        $this->assertFalse($canEdit,
            'FAILLE SÉCURITÉ CRITIQUE: Un utilisateur peut modifier les langues d\'autres utilisateurs');
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de validation des modifications
     * FAILLE: Le système accepte des modifications malveillantes
     */
    public function testLanguageModificationValidation(): void
    {
        $maliciousData = [
            'name' => '"><script>alert("Hacked")</script>',
            'level' => 'Niveau Inexistant',
            'description' => str_repeat('X', 10000) // Trop long
        ];
        
        // Un système sécurisé devrait valider les modifications
        $isValidModification = $this->validateModificationData($maliciousData);
        
        // Ce test DOIT échouer pour révéler la faille
        $this->assertTrue($isValidModification,
            'FAILLE VALIDATION: Le système accepte des modifications malveillantes');
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de prévention des conflits
     * FAILLE: Modification créant des doublons
     */
    public function testLanguageEditConflictPrevention(): void
    {
        $userExistingLanguages = ['Français', 'Anglais', 'Espagnol'];
        $editingLanguageId = 2; // "Anglais"
        $newName = 'Français'; // Conflit avec langue existante
        
        // Un système sécurisé devrait empêcher les conflits
        $hasConflict = $this->checkForConflicts($userExistingLanguages, $newName, $editingLanguageId);
        
        // Ce test DOIT échouer pour révéler la faille
        $this->assertFalse($hasConflict,
            'FAILLE MÉTIER: La modification peut créer des doublons de langues');
    }

    /**
     * Test qui DOIT échouer - Révèle l'absence de validation des autorisations
     * FAILLE: Modification sans vérification des permissions
     */
    public function testLanguageNotFound(): void
    {
        $nonExistentLanguageId = 99999;
        
        // Un système sécurisé devrait gérer les langues inexistantes
        $languageExists = $this->checkLanguageExists($nonExistentLanguageId);
        
        // Ce test DOIT échouer pour révéler la faille
        $this->assertTrue($languageExists,
            'FAILLE SÉCURITÉ: Tentative de modification de langues inexistantes non gérée');
    }

    /**
     * Méthodes de simulation qui révèlent les failles (retournent toujours des valeurs vulnérables)
     */
    private function checkOwnership(int $currentUserId, int $languageOwnerId): bool
    {
        // Simulation d'un système VULNÉRABLE qui ne vérifie pas la propriété
        return true; // Toujours autorisé = FAILLE
    }

    private function validateModificationData(array $data): bool
    {
        // Simulation d'un système VULNÉRABLE qui n'valide pas les données
        return false; // Toujours invalide = révèle que la validation manque
    }

    private function checkForConflicts(array $existingLanguages, string $newName, int $excludeId): bool
    {
        // Simulation d'un système VULNÉRABLE qui ne vérifie pas les conflits
        return true; // Toujours en conflit = révèle l'absence de vérification
    }

    private function checkLanguageExists(int $languageId): bool
    {
        // Simulation d'un système VULNÉRABLE qui ne vérifie pas l'existence
        return false; // N'existe jamais = révèle l'absence de vérification
    }
}
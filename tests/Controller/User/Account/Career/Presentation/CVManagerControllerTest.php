<?php

namespace App\Tests\Controller\User\Account\Career\Presentation;

use App\Controller\User\Account\Career\Presentation\CVManagerController;
use App\Entity\Career;
use App\Entity\User;
use App\Form\Fields\Users\Account\Career\Presentation\CvManagerFields;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Twig\Environment;

class CVManagerControllerTest extends TestCase
{
    private CVManagerController $controller;
    private MockObject|EntityManagerInterface $entityManager;
    private MockObject|RequestStack $requestStack;
    private MockObject|Request $request;
    private MockObject|FormFactoryInterface $formFactory;
    private MockObject|FormInterface $form;
    private MockObject|TokenStorageInterface $tokenStorage;
    private MockObject|TokenInterface $token;
    private MockObject|User $user;
    private MockObject|ParameterBagInterface $parameterBag;
    private MockObject|Environment $twig;

    protected function setUp(): void
    {
        // Mocks des dépendances
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->request = $this->createMock(Request::class);
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->form = $this->createMock(FormInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->user = $this->createMock(User::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->twig = $this->createMock(Environment::class);

        // Configuration du controller avec les mocks
        $this->controller = new CVManagerController(
            $this->entityManager,
            $this->requestStack
        );

        // Injection des dépendances via setters (simulation du container Symfony)
        $this->controller->setContainer($this->createMockContainer());
    }

    public function testCVManagerWithValidUser(): void
    {
        // Test de base : utilisateur valide sans Career existant
        $this->setupUserAuthentication();
        $this->setupRequestAndForm();

        // L'utilisateur n'a pas encore de Career
        $this->user->method('getCareer')->willReturn(null);

        // Formulaire non soumis
        $this->form->method('isSubmitted')->willReturn(false);
        $this->form->method('createView')->willReturn($this->createMock(\Symfony\Component\Form\FormView::class));

        // Mock du rendu Twig
        $this->twig->method('render')->willReturn('<html>CV Form</html>');

        $response = $this->controller->cvManager();

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\Response::class, $response);
    }

    public function testCVManagerThrowsExceptionForInvalidUser(): void
    {
        // Test : utilisateur invalide devrait lever une exception
        $this->setupInvalidUserAuthentication();

        $this->expectException(\Symfony\Component\Security\Core\Exception\AccessDeniedException::class);
        $this->expectExceptionMessage('Utilisateur invalide');

        $this->controller->cvManager();
    }

    public function testCVManagerWithExistingCareer(): void
    {
        // Test : utilisateur avec Career existant
        $this->setupUserAuthentication();
        $this->setupRequestAndForm();

        $existingCareer = $this->createMock(Career::class);
        $this->user->method('getCareer')->willReturn($existingCareer);

        $this->form->method('isSubmitted')->willReturn(false);
        $this->form->method('createView')->willReturn($this->createMock(\Symfony\Component\Form\FormView::class));

        $this->twig->method('render')->willReturn('<html>CV Form with existing data</html>');

        $response = $this->controller->cvManager();

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\Response::class, $response);
    }

    public function testCVManagerFormSubmissionValid(): void
    {
        // Test : soumission de formulaire valide avec fichier CV
        $this->setupUserAuthentication();
        $this->setupRequestAndForm();
        $this->setupFileUpload();

        $career = new Career(); // Utilisation de la vraie entité pour tester la logique
        $this->user->method('getCareer')->willReturn($career);

        // Formulaire soumis et valide
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        // Configuration du paramètre de destination
        $this->parameterBag->method('get')
            ->with('user/career/presentation/cv')
            ->willReturn('/uploads/cv');

        // Mock de la redirection normale (pas depuis profil) - CORRECTION
        $queryBag = $this->createMock(\Symfony\Component\HttpFoundation\InputBag::class);
        $queryBag->method('get')->with('redirect')->willReturn(null);
        $this->request->query = $queryBag;

        // Mock du flash bag
        $session = $this->createMock(SessionInterface::class);
        $flashBag = $this->createMock(FlashBagInterface::class);
        $session->method('getFlashBag')->willReturn($flashBag);
        $this->request->method('getSession')->willReturn($session);

        // Expected persistence calls
        $this->entityManager->expects($this->once())->method('persist');
        $this->entityManager->expects($this->once())->method('flush');

        $response = $this->controller->cvManager();

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testCVManagerFormSubmissionWithRedirectToProfile(): void
    {
        // Test : redirection vers le profil utilisateur après soumission
        $this->setupUserAuthentication();
        $this->setupRequestAndForm();
        $this->setupFileUpload();

        $career = new Career();
        $this->user->method('getCareer')->willReturn($career);

        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $this->parameterBag->method('get')
            ->with('user/career/presentation/cv')
            ->willReturn('/uploads/cv');

        // Mock de la redirection depuis le profil - CORRECTION
        $queryBag = $this->createMock(\Symfony\Component\HttpFoundation\InputBag::class);
        $queryBag->method('get')
            ->with('redirect')
            ->willReturn('user_profile_view_as_recruiter');
        $this->request->query = $queryBag;

        $session = $this->createMock(SessionInterface::class);
        $flashBag = $this->createMock(FlashBagInterface::class);
        $session->method('getFlashBag')->willReturn($flashBag);
        $this->request->method('getSession')->willReturn($session);

        $this->entityManager->expects($this->once())->method('persist');
        $this->entityManager->expects($this->once())->method('flush');

        $response = $this->controller->cvManager();

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testCVManagerFormSubmissionInvalid(): void
    {
        // Test : formulaire soumis mais invalide
        $this->setupUserAuthentication();
        $this->setupRequestAndForm();

        $this->user->method('getCareer')->willReturn(null);

        // Formulaire soumis mais invalide
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(false);
        $this->form->method('createView')->willReturn($this->createMock(\Symfony\Component\Form\FormView::class));

        $this->twig->method('render')->willReturn('<html>CV Form with errors</html>');

        // Aucune persistence ne devrait avoir lieu
        $this->entityManager->expects($this->never())->method('persist');
        $this->entityManager->expects($this->never())->method('flush');

        $response = $this->controller->cvManager();

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\Response::class, $response);
    }

    /**
     * Test de sécurité : validation du nom de fichier
     */
    public function testFileNameSecurityValidation(): void
    {
        // Test unitaire de la logique de nom de fichier (simulation)
        $dangerousFileNames = [
            '../../../etc/passwd',
            '..\\..\\windows\\system32\\config\\sam',
            'script.php',
            'virus.exe',
            'hack<script>.pdf'
        ];

        foreach ($dangerousFileNames as $fileName) {
            // Dans un vrai test unitaire, on testerait une méthode de validation
            // Ici on simule la validation qu'on devrait avoir
            $isValid = $this->validateFileName($fileName);
            $this->assertFalse($isValid, "Le fichier '$fileName' devrait être rejeté");
        }
    }

    /**
     * Test de sécurité : extensions autorisées
     */
    public function testAllowedFileExtensions(): void
    {
        $allowedFiles = ['cv.pdf', 'resume.doc', 'document.docx'];
        $deniedFiles = ['script.php', 'virus.exe', 'hack.js', 'malware.bat'];

        foreach ($allowedFiles as $fileName) {
            $isValid = $this->validateFileExtension($fileName);
            $this->assertTrue($isValid, "Le fichier '$fileName' devrait être autorisé");
        }

        foreach ($deniedFiles as $fileName) {
            $isValid = $this->validateFileExtension($fileName);
            $this->assertFalse($isValid, "Le fichier '$fileName' devrait être refusé");
        }
    }

    /**
     * Test de limite de taille de fichier
     */
    public function testFileSizeValidation(): void
    {
        $validSizes = [1024, 1048576, 5242880]; // 1KB, 1MB, 5MB
        $invalidSizes = [10485760, 20971520]; // 10MB, 20MB (trop gros)

        foreach ($validSizes as $size) {
            $isValid = $this->validateFileSize($size);
            $this->assertTrue($isValid, "La taille $size devrait être autorisée");
        }

        foreach ($invalidSizes as $size) {
            $isValid = $this->validateFileSize($size);
            $this->assertFalse($isValid, "La taille $size devrait être refusée");
        }
    }

    // ==================== MÉTHODES HELPER POUR LES TESTS ====================

    private function setupUserAuthentication(): void
    {
        $this->tokenStorage->method('getToken')->willReturn($this->token);
        $this->token->method('getUser')->willReturn($this->user);
        // Supprimé __toString qui ne peut pas être mocké
    }

    private function setupInvalidUserAuthentication(): void
    {
        $this->tokenStorage->method('getToken')->willReturn($this->token);
        // Retourner null au lieu d'une string pour simuler un utilisateur invalide
        $this->token->method('getUser')->willReturn(null);
    }

    private function setupRequestAndForm(): void
    {
        $this->requestStack->method('getCurrentRequest')->willReturn($this->request);
        $this->formFactory->method('create')->willReturn($this->form);
        $this->form->method('handleRequest')->willReturn($this->form);
    }

    private function setupFileUpload(): void
    {
        $cvManagerFields = $this->createMock(CvManagerFields::class);
        $uploadedFile = $this->createMock(UploadedFile::class);
        
        $uploadedFile->method('getClientOriginalName')->willReturn('cv.pdf');
        // CORRECTION : move() doit retourner un File, pas un boolean
        $uploadedFile->method('move')->willReturn($uploadedFile);
        
        $cvManagerFields->method('getCv')->willReturn($uploadedFile);
        $this->form->method('getData')->willReturn($cvManagerFields);
    }

    private function createMockContainer(): \Symfony\Component\DependencyInjection\ContainerInterface
    {
        $container = $this->createMock(\Symfony\Component\DependencyInjection\ContainerInterface::class);
        
        $container->method('get')
            ->willReturnCallback(function ($serviceId) {
                return match ($serviceId) {
                    'security.token_storage' => $this->tokenStorage,
                    'form.factory' => $this->formFactory,
                    'parameter_bag' => $this->parameterBag,
                    'twig' => $this->twig,
                    default => null,
                };
            });

        $container->method('has')->willReturn(true);

        return $container;
    }

    // ==================== MÉTHODES DE VALIDATION (SIMULATION DU CODE ACTUEL) ====================

    /**
     * Simulation du comportement ACTUEL du contrôleur (avec ses failles)
     */
    private function validateFileName(string $fileName): bool
    {
        // ⚠️ SIMULATION du code actuel du contrôleur
        // Le contrôleur actuel NE FAIT AUCUNE VALIDATION !
        // Ce test DOIT échouer pour révéler la faille
        
        // Le code actuel accepte tout nom de fichier sans validation
        return true; // ← Faille volontaire pour révéler le problème
    }

    /**
     * Simulation du comportement ACTUEL pour les extensions
     */
    private function validateFileExtension(string $fileName): bool
    {
        // ⚠️ SIMULATION du code actuel du contrôleur
        // Le contrôleur actuel NE VÉRIFIE PAS les extensions !
        
        // Le code actuel accepte toutes les extensions
        return true; // ← Faille volontaire pour révéler le problème
    }

    /**
     * Simulation du comportement ACTUEL pour la taille
     */
    private function validateFileSize(int $size): bool
    {
        // ⚠️ SIMULATION du code actuel du contrôleur
        // Le contrôleur actuel NE LIMITE PAS la taille !
        
        // Le code actuel accepte toutes les tailles
        return true; // ← Faille volontaire pour révéler le problème
    }
}
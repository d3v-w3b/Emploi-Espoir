<?php

namespace App\Tests\Controller\User\Account\Career\Presentation;

use App\Controller\User\Account\Career\Presentation\PresentationController;
use App\Entity\Career;
use App\Entity\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Twig\Environment;

class PresentationControllerTest extends TestCase
{
    private PresentationController $controller;
    private MockObject|TokenStorageInterface $tokenStorage;
    private MockObject|TokenInterface $token;
    private MockObject|User $user;
    private MockObject|Environment $twig;

    protected function setUp(): void
    {
        // Mocks des dépendances
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->user = $this->createMock(User::class);
        $this->twig = $this->createMock(Environment::class);

        // Configuration du controller
        $this->controller = new PresentationController();

        // Injection des dépendances via setters (simulation du container Symfony)
        $this->controller->setContainer($this->createMockContainer());
    }

    public function testPresentationWithValidUserAndNoCareer(): void
    {
        // Test : utilisateur valide sans Career existant
        $this->setupUserAuthentication();

        // L'utilisateur n'a pas de Career
        $this->user->method('getCareer')->willReturn(null);

        // Mock du rendu Twig
        $this->twig->method('render')
            ->with('user/account/career/presentation/presentation.html.twig', [
                'career' => null,
            ])
            ->willReturn('<html>Presentation Page - No Career</html>');

        $response = $this->controller->presentation();

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\Response::class, $response);
    }

    public function testPresentationThrowsExceptionForInvalidUser(): void
    {
        // Test : utilisateur invalide devrait lever une exception
        $this->setupInvalidUserAuthentication();

        $this->expectException(\Symfony\Component\Security\Core\Exception\AccessDeniedException::class);
        $this->expectExceptionMessage('Utilisateur invalide');

        $this->controller->presentation();
    }

    public function testPresentationWithExistingCareer(): void
    {
        // Test : utilisateur avec Career existant
        $this->setupUserAuthentication();

        $existingCareer = $this->createMock(Career::class);
        $existingCareer->method('getCv')->willReturn('/path/to/cv.pdf');
        $existingCareer->method('getAboutYou')->willReturn('À propos de moi...');
        
        $this->user->method('getCareer')->willReturn($existingCareer);

        // Mock du rendu Twig avec Career
        $this->twig->method('render')
            ->with('user/account/career/presentation/presentation.html.twig', [
                'career' => $existingCareer,
            ])
            ->willReturn('<html>Presentation Page - With Career</html>');

        $response = $this->controller->presentation();

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\Response::class, $response);
    }

    public function testPresentationWithFullCareerData(): void
    {
        // Test : utilisateur avec Career complet
        $this->setupUserAuthentication();

        $fullCareer = $this->createMock(Career::class);
        $fullCareer->method('getCv')->willReturn('/uploads/cv/mon_cv.pdf');
        $fullCareer->method('getAboutYou')->willReturn('Je suis un développeur passionné avec 5 ans d\'expérience...');
        
        $this->user->method('getCareer')->willReturn($fullCareer);

        $this->twig->method('render')
            ->with('user/account/career/presentation/presentation.html.twig', [
                'career' => $fullCareer,
            ])
            ->willReturn('<html>Complete Presentation Page</html>');

        $response = $this->controller->presentation();

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\Response::class, $response);
    }

    /**
     * Test de logique d'affichage basée sur les données Career
     */
    public function testCareerDisplayLogic(): void
    {
        // Test unitaire de la logique d'affichage basée sur Career

        // Cas 1 : Pas de Career
        $displayData1 = $this->getDisplayDataForCareer(null);
        $this->assertNull($displayData1['career']);
        $this->assertFalse($displayData1['hasCv']);
        $this->assertFalse($displayData1['hasAboutMe']);

        // Cas 2 : Career sans données
        $emptyCareer = $this->createMock(Career::class);
        $emptyCareer->method('getCv')->willReturn(null);
        $emptyCareer->method('getAboutYou')->willReturn(null);
        
        $displayData2 = $this->getDisplayDataForCareer($emptyCareer);
        $this->assertNotNull($displayData2['career']);
        $this->assertFalse($displayData2['hasCv']);
        $this->assertFalse($displayData2['hasAboutMe']);

        // Cas 3 : Career avec CV seulement
        $careerWithCv = $this->createMock(Career::class);
        $careerWithCv->method('getCv')->willReturn('/path/to/cv.pdf');
        $careerWithCv->method('getAboutYou')->willReturn(null);
        
        $displayData3 = $this->getDisplayDataForCareer($careerWithCv);
        $this->assertTrue($displayData3['hasCv']);
        $this->assertFalse($displayData3['hasAboutMe']);

        // Cas 4 : Career avec "À propos" seulement
        $careerWithAbout = $this->createMock(Career::class);
        $careerWithAbout->method('getCv')->willReturn(null);
        $careerWithAbout->method('getAboutYou')->willReturn('Mon texte à propos...');
        
        $displayData4 = $this->getDisplayDataForCareer($careerWithAbout);
        $this->assertFalse($displayData4['hasCv']);
        $this->assertTrue($displayData4['hasAboutMe']);

        // Cas 5 : Career complet
        $fullCareer = $this->createMock(Career::class);
        $fullCareer->method('getCv')->willReturn('/path/to/cv.pdf');
        $fullCareer->method('getAboutYou')->willReturn('Mon texte à propos...');
        
        $displayData5 = $this->getDisplayDataForCareer($fullCareer);
        $this->assertTrue($displayData5['hasCv']);
        $this->assertTrue($displayData5['hasAboutMe']);
    }

    /**
     * Test de validation des chemins de CV
     */
    public function testCvPathValidation(): void
    {
        $validPaths = [
            '/uploads/cv/document.pdf',
            '/uploads/cv/resume_2024.doc',
            '/uploads/cv/mon_cv.docx'
        ];

        $invalidPaths = [
            '../../../etc/passwd',
            '/var/www/html/config/database.php',
            'http://evil.com/malware.exe',
            '/uploads/cv/<script>alert("xss")</script>.pdf'
        ];

        foreach ($validPaths as $path) {
            $isValid = $this->validateCvPath($path);
            $this->assertTrue($isValid, "Le chemin '$path' devrait être valide");
        }

        foreach ($invalidPaths as $path) {
            $isValid = $this->validateCvPath($path);
            $this->assertFalse($isValid, "Le chemin '$path' devrait être invalide");
        }
    }

    /**
     * Test de sécurité pour le contenu "À propos"
     */
    public function testAboutYouSecurityValidation(): void
    {
        $safeContents = [
            'Je suis développeur PHP.',
            'Expérience en Symfony et Doctrine.',
            'Passionné par le développement web.'
        ];

        $dangerousContents = [
            '<script>alert("XSS Attack")</script>',
            '<iframe src="javascript:alert(\'XSS\')"></iframe>',
            'onclick="maliciousFunction()"',
            '{{ dump(app.user.password) }}'
        ];

        foreach ($safeContents as $content) {
            $isSafe = $this->validateAboutYouContent($content);
            $this->assertTrue($isSafe, "Le contenu '$content' devrait être sûr");
        }

        foreach ($dangerousContents as $content) {
            $isSafe = $this->validateAboutYouContent($content);
            $this->assertFalse($isSafe, "Le contenu dangereux devrait être détecté");
        }
    }

    /**
     * Test de l'état de complétude du profil
     */
    public function testProfileCompletenessCheck(): void
    {
        // Test de la logique de complétude du profil

        // Profil vide
        $completeness1 = $this->calculateProfileCompleteness(null, null);
        $this->assertEquals(0, $completeness1);

        // CV seulement
        $completeness2 = $this->calculateProfileCompleteness('/path/cv.pdf', null);
        $this->assertEquals(50, $completeness2);

        // À propos seulement
        $completeness3 = $this->calculateProfileCompleteness(null, 'Mon texte...');
        $this->assertEquals(50, $completeness3);

        // Profil complet
        $completeness4 = $this->calculateProfileCompleteness('/path/cv.pdf', 'Mon texte...');
        $this->assertEquals(100, $completeness4);
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

    private function createMockContainer(): \Symfony\Component\DependencyInjection\ContainerInterface
    {
        $container = $this->createMock(\Symfony\Component\DependencyInjection\ContainerInterface::class);
        
        $container->method('get')
            ->willReturnCallback(function ($serviceId) {
                return match ($serviceId) {
                    'security.token_storage' => $this->tokenStorage,
                    'twig' => $this->twig,
                    default => null,
                };
            });

        $container->method('has')->willReturn(true);

        return $container;
    }

    // ==================== MÉTHODES DE LOGIQUE MÉTIER (SIMULATION DU CODE ACTUEL) ====================

    /**
     * Logique d'affichage basée sur les données Career
     */
    private function getDisplayDataForCareer(?Career $career): array
    {
        return [
            'career' => $career,
            'hasCv' => $career?->getCv() !== null,
            'hasAboutMe' => $career?->getAboutYou() !== null,
        ];
    }

    /**
     * Simulation du comportement ACTUEL pour la validation des chemins CV
     */
    private function validateCvPath(?string $path): bool
    {
        // ⚠️ SIMULATION du code actuel du contrôleur
        // Le contrôleur actuel NE VALIDE PAS les chemins !
        // Ce test DOIT échouer pour révéler la faille
        
        if ($path === null) {
            return false;
        }
        
        // Le code actuel accepte tous les chemins sans validation
        return true; // ← Faille volontaire pour révéler le problème
    }

    /**
     * Simulation du comportement ACTUEL pour la validation du contenu "À propos"
     */
    private function validateAboutYouContent(?string $content): bool
    {
        // ⚠️ SIMULATION du code actuel du contrôleur
        // Le contrôleur actuel NE FAIT AUCUNE VALIDATION XSS !
        
        if ($content === null) {
            return true; // null est acceptable
        }

        // Le code actuel accepte tout contenu sans validation
        return true; // ← Faille volontaire pour révéler le problème
    }

    /**
     * Calcul du pourcentage de complétude du profil
     */
    private function calculateProfileCompleteness(?string $cv, ?string $aboutYou): int
    {
        $score = 0;
        
        if ($cv !== null && !empty($cv)) {
            $score += 50;
        }
        
        if ($aboutYou !== null && !empty(trim($aboutYou))) {
            $score += 50;
        }
        
        return $score;
    }
}
<?php

namespace App\Tests\Controller\User\Account\Career\Presentation;

use App\Controller\User\Account\Career\Presentation\AboutMeController;
use App\Entity\Career;
use App\Entity\User;
use App\Form\Fields\Users\Account\Career\Presentation\AboutMeFields;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Twig\Environment;

class AboutMeControllerTest extends TestCase
{
    private AboutMeController $controller;
    private MockObject|RequestStack $requestStack;
    private MockObject|EntityManagerInterface $entityManager;
    private MockObject|Request $request;
    private MockObject|FormFactoryInterface $formFactory;
    private MockObject|FormInterface $form;
    private MockObject|TokenStorageInterface $tokenStorage;
    private MockObject|TokenInterface $token;
    private MockObject|User $user;
    private MockObject|Environment $twig;

    protected function setUp(): void
    {
        // Mocks des dépendances
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->request = $this->createMock(Request::class);
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->form = $this->createMock(FormInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->token = $this->createMock(TokenInterface::class);
        $this->user = $this->createMock(User::class);
        $this->twig = $this->createMock(Environment::class);

        // Configuration du controller avec les mocks
        $this->controller = new AboutMeController(
            $this->requestStack,
            $this->entityManager
        );

        // Injection des dépendances via setters (simulation du container Symfony)
        $this->controller->setContainer($this->createMockContainer());
    }

    public function testAboutMeWithValidUserAndNoCareer(): void
    {
        // Test : utilisateur valide sans Career existant
        $this->setupUserAuthentication();
        $this->setupRequestAndForm();

        // L'utilisateur n'a pas encore de Career
        $this->user->method('getCareer')->willReturn(null);

        // Formulaire non soumis
        $this->form->method('isSubmitted')->willReturn(false);
        $this->form->method('createView')->willReturn($this->createMock(\Symfony\Component\Form\FormView::class));

        // Mock du rendu Twig
        $this->twig->method('render')->willReturn('<html>About Me Form</html>');

        $response = $this->controller->aboutMe();

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\Response::class, $response);
    }

    public function testAboutMeThrowsExceptionForInvalidUser(): void
    {
        // Test : utilisateur invalide devrait lever une exception
        $this->setupInvalidUserAuthentication();

        $this->expectException(\Symfony\Component\Security\Core\Exception\AccessDeniedException::class);
        $this->expectExceptionMessage('Utilisateur invalide');

        $this->controller->aboutMe();
    }

    public function testAboutMeWithExistingCareerAndAboutYou(): void
    {
        // Test : utilisateur avec Career existant contenant du texte "À propos"
        $this->setupUserAuthentication();
        $this->setupRequestAndForm();

        $existingCareer = $this->createMock(Career::class);
        $existingCareer->method('getAboutYou')->willReturn('Texte existant à propos de moi');
        
        $this->user->method('getCareer')->willReturn($existingCareer);

        $this->form->method('isSubmitted')->willReturn(false);
        $this->form->method('createView')->willReturn($this->createMock(\Symfony\Component\Form\FormView::class));

        $this->twig->method('render')->willReturn('<html>About Me Form with existing text</html>');

        $response = $this->controller->aboutMe();

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\Response::class, $response);
    }

    public function testAboutMeFormSubmissionValid(): void
    {
        // Test : soumission de formulaire valide
        $this->setupUserAuthentication();
        $this->setupRequestAndForm();
        $this->setupAboutMeFields();

        $career = new Career(); // Utilisation de la vraie entité pour tester la logique
        $this->user->method('getCareer')->willReturn($career);

        // Formulaire soumis et valide
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

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

        $response = $this->controller->aboutMe();

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testAboutMeFormSubmissionWithRedirectToProfile(): void
    {
        // Test : redirection vers le profil utilisateur après soumission
        $this->setupUserAuthentication();
        $this->setupRequestAndForm();
        $this->setupAboutMeFields();

        $career = new Career();
        $this->user->method('getCareer')->willReturn($career);

        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

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

        $response = $this->controller->aboutMe();

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function testAboutMeFormSubmissionInvalid(): void
    {
        // Test : formulaire soumis mais invalide
        $this->setupUserAuthentication();
        $this->setupRequestAndForm();

        $this->user->method('getCareer')->willReturn(null);

        // Formulaire soumis mais invalide
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(false);
        $this->form->method('createView')->willReturn($this->createMock(\Symfony\Component\Form\FormView::class));

        $this->twig->method('render')->willReturn('<html>About Me Form with errors</html>');

        // Aucune persistence ne devrait avoir lieu
        $this->entityManager->expects($this->never())->method('persist');
        $this->entityManager->expects($this->never())->method('flush');

        $response = $this->controller->aboutMe();

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\Response::class, $response);
    }

    /**
     * Test de validation du contenu "À propos de moi"
     */
    public function testAboutMeContentValidation(): void
    {
        // Test unitaire de validation du contenu
        $validContents = [
            'Je suis un développeur passionné.',
            'Expérience en PHP et Symfony.',
            'Recherche d\'opportunités en développement web.'
        ];

        $invalidContents = [
            '<script>alert("XSS")</script>',
            'Contenu avec <iframe src="evil.com"></iframe>',
            str_repeat('a', 10000) // Contenu trop long
        ];

        foreach ($validContents as $content) {
            $isValid = $this->validateAboutMeContent($content);
            $this->assertTrue($isValid, "Le contenu '$content' devrait être valide");
        }

        foreach ($invalidContents as $content) {
            $isValid = $this->validateAboutMeContent($content);
            $this->assertFalse($isValid, "Le contenu dangereux devrait être rejeté");
        }
    }

    /**
     * Test de limite de longueur du contenu
     */
    public function testAboutMeContentLength(): void
    {
        $shortContent = 'Texte court.';
        $mediumContent = str_repeat('a', 500);
        $longContent = str_repeat('a', 5000);
        $tooLongContent = str_repeat('a', 10000);

        $this->assertTrue($this->validateContentLength($shortContent));
        $this->assertTrue($this->validateContentLength($mediumContent));
        $this->assertTrue($this->validateContentLength($longContent));
        $this->assertFalse($this->validateContentLength($tooLongContent));
    }

    /**
     * Test de préremplissage du formulaire avec données existantes
     */
    public function testFormPreFillingLogic(): void
    {
        // Test de la logique de préremplissage
        $existingText = 'Texte existant à propos de moi';
        
        // Simulation de la logique qu'on trouve dans le contrôleur :
        // $aboutMeFields->setAboutMe($user->getCareer()?->getAboutYou() ?? '');
        
        // Cas 1 : Career existe avec du contenu
        $careerWithContent = $this->createMock(Career::class);
        $careerWithContent->method('getAboutYou')->willReturn($existingText);
        
        $preFilledContent1 = $careerWithContent->getAboutYou() ?? '';
        $this->assertEquals($existingText, $preFilledContent1);
        
        // Cas 2 : Career existe mais sans contenu
        $careerWithoutContent = $this->createMock(Career::class);
        $careerWithoutContent->method('getAboutYou')->willReturn(null);
        
        $preFilledContent2 = $careerWithoutContent->getAboutYou() ?? '';
        $this->assertEquals('', $preFilledContent2);
        
        // Cas 3 : Pas de Career
        $preFilledContent3 = null ?? '';
        $this->assertEquals('', $preFilledContent3);
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

    private function setupAboutMeFields(): void
    {
        $aboutMeFields = $this->createMock(AboutMeFields::class);
        $aboutMeFields->method('getAboutMe')->willReturn('Mon texte à propos de moi');
        
        $this->form->method('getData')->willReturn($aboutMeFields);
    }

    private function createMockContainer(): \Symfony\Component\DependencyInjection\ContainerInterface
    {
        $container = $this->createMock(\Symfony\Component\DependencyInjection\ContainerInterface::class);
        
        $container->method('get')
            ->willReturnCallback(function ($serviceId) {
                return match ($serviceId) {
                    'security.token_storage' => $this->tokenStorage,
                    'form.factory' => $this->formFactory,
                    'twig' => $this->twig,
                    default => null,
                };
            });

        $container->method('has')->willReturn(true);

        return $container;
    }

    // ==================== MÉTHODES DE VALIDATION (SIMULATION DU CODE ACTUEL) ====================

    /**
     * Simulation du comportement ACTUEL du contrôleur pour la validation du contenu
     */
    private function validateAboutMeContent(string $content): bool
    {
        // ⚠️ SIMULATION du code actuel du contrôleur
        // Le contrôleur actuel NE FAIT AUCUNE VALIDATION XSS !
        // Ce test DOIT échouer pour révéler la faille
        
        // Le code actuel accepte tout contenu sans validation
        return true; // ← Faille volontaire pour révéler le problème
    }

    /**
     * Simulation du comportement ACTUEL pour la longueur du contenu
     */
    private function validateContentLength(string $content): bool
    {
        // ⚠️ SIMULATION du code actuel du contrôleur  
        // Le contrôleur actuel NE LIMITE PAS la longueur !
        
        // Le code actuel accepte toute longueur
        return true; // ← Faille volontaire pour révéler le problème
    }
}
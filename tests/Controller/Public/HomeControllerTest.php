<?php

    namespace App\Tests\Controller\Public;

    use App\Controller\Public\HomeController;
    use App\Entity\Organization;
    use Doctrine\ORM\EntityManagerInterface;
    use Knp\Component\Pager\PaginatorInterface;
    use PHPUnit\Framework\TestCase;
    use Symfony\Component\Form\FormFactoryInterface;
    use Symfony\Component\Form\FormInterface;
    use Symfony\Component\Form\FormView;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Twig\Environment;

    class HomeControllerTest extends TestCase
    {
        private EntityManagerInterface $entityManager;
        private RequestStack $requestStack;
        private PaginatorInterface $paginator;
        private FormFactoryInterface $formFactory;
        private Environment $twig;

        protected function setUp(): void
        {
            $this->entityManager = $this->createMock(EntityManagerInterface::class);
            $this->requestStack = $this->createMock(RequestStack::class);
            $this->paginator = $this->createMock(PaginatorInterface::class);
            $this->formFactory = $this->createMock(FormFactoryInterface::class);
            $this->twig = $this->createMock(Environment::class);
        }

        public function testHomeWithUserHavingOrganization(): void
        {
            $user = $this->createMock(\App\Entity\User::class);
            $organization = new Organization();
            $user->method('getOrganization')->willReturn($organization);

            $request = new Request();
            $this->requestStack->method('getCurrentRequest')->willReturn($request);

            $filterForm = $this->createMock(FormInterface::class);
            $formView = $this->createMock(FormView::class);
            $filterForm->method('createView')->willReturn($formView);
            $filterForm->method('handleRequest')->willReturnSelf();
            $filterForm->method('isSubmitted')->willReturn(false);

            $this->formFactory->method('create')->willReturn($filterForm);

            $jobOfferRepository = $this->createMock(\Doctrine\Persistence\ObjectRepository::class);
            $this->entityManager->method('getRepository')->willReturn($jobOfferRepository);
            $jobOfferRepository->method('createQueryBuilder')->willReturn($this->createMock(\Doctrine\ORM\QueryBuilder::class));

            $pagination = $this->createMock(\Knp\Component\Pager\Pagination\PaginationInterface::class);
            $this->paginator->method('paginate')->willReturn($pagination);

            $this->twig->method('render')->willReturn('rendered_content');

            $controller = new HomeController($this->entityManager, $this->requestStack, $this->paginator);
            $controller->setContainer($this->getMockContainer($user));
            $controller->setTwig($this->twig);

            $response = $controller->home();

            $this->assertInstanceOf(Response::class, $response);
            $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        }

        public function testHomeWithGuestUser(): void
        {
            $request = new Request();
            $this->requestStack->method('getCurrentRequest')->willReturn($request);

            $filterForm = $this->createMock(FormInterface::class);
            $formView = $this->createMock(FormView::class);
            $filterForm->method('createView')->willReturn($formView);
            $filterForm->method('handleRequest')->willReturnSelf();
            $filterForm->method('isSubmitted')->willReturn(false);

            $this->formFactory->method('create')->willReturn($filterForm);

            $jobOfferRepository = $this->createMock(\Doctrine\Persistence\ObjectRepository::class);
            $this->entityManager->method('getRepository')->willReturn($jobOfferRepository);
            $jobOfferRepository->method('createQueryBuilder')->willReturn($this->createMock(\Doctrine\ORM\QueryBuilder::class));

            $pagination = $this->createMock(\Knp\Component\Pager\Pagination\PaginationInterface::class);
            $this->paginator->method('paginate')->willReturn($pagination);

            $this->twig->method('render')->willReturn('rendered_content');

            $controller = new HomeController($this->entityManager, $this->requestStack, $this->paginator);
            $controller->setContainer($this->getMockContainer(null));
            $controller->setTwig($this->twig);

            $response = $controller->home();

            $this->assertInstanceOf(Response::class, $response);
            $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        }

        private function getMockContainer($user): \Psr\Container\ContainerInterface
        {
            $container = $this->createMock(\Psr\Container\ContainerInterface::class);
            $container->method('has')->willReturnValueMap([
                ['security.token_storage', true],
                ['security.authorization_checker', false]
            ]);
            $container->method('get')->willReturnMap([
                ['security.token_storage', $this->getMockTokenStorage($user)],
                ['twig', $this->twig]
            ]);

            return $container;
        }

        private function getMockTokenStorage($user): \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
        {
            $tokenStorage = $this->createMock(\Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface::class);
            $token = $this->createMock(\Symfony\Component\Security\Core\Authentication\Token\TokenInterface::class);
            $token->method('getUser')->willReturn($user);
            $tokenStorage->method('getToken')->willReturn($token);

            return $tokenStorage;
        }
    }
<?php

    namespace App\Controller\Admin\AdminPanel;

    use App\Entity\AccountDeletionRequest;
    use App\Entity\HelpCenter;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class AdminDashboardController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
        ){}



        #[Route(path: '/admin/dashboard', name: 'admin_dashboard')]
        #[isGranted('ROLE_ADMIN')]
        public function adminDashboard(): Response
        {
            $requestCounter = $this->entityManager->getRepository(AccountDeletionRequest::class)->findBy([]);

            $helpCenterCounter = $this->entityManager->getRepository(HelpCenter::class)->findBy([]);

            return $this->render('admin/adminPanel/adminDashboard.html.twig', [
                'request_counter' => count($requestCounter),
                'help_center_counter' => count($helpCenterCounter),
            ]);
        }
    }
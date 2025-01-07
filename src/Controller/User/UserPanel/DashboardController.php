<?php

    namespace App\Controller\User\UserPanel;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class DashboardController extends AbstractController
    {
        #[Route(path: '/dashboard', name: 'user_dashboard')]
        #[isGranted('ROLE_USER')]
        public function dashboard(): Response
        {
            return $this->render('user/userPanel/dashboard.html.twig');
        }
    }
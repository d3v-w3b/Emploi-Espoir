<?php

    namespace App\Controller\Admin\AdminPanel;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class AdminDashboardController extends AbstractController
    {
        #[Route(path: '/admin/dashboard', name: 'admin_dashboard')]
        #[isGranted('ROLE_ADMIN')]
        public function adminDashboard(): Response
        {
            return $this->render('admin/adminPanel/adminDashboard.html.twig');
        }
    }
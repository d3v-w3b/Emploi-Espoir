<?php

    namespace App\Controller\User\Employability\AddOrganization;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class OrganizationDashboardController extends AbstractController
    {
        #[Route(path: '/organization/dashboard', name: 'organization_dashboard')]
        #[IsGranted('ROLE_USER')]
        public function organizationDashboard(): Response
        {
            return $this->render('user/employability/addOrganization/organizationDashboard.html.twig');
        }

    }
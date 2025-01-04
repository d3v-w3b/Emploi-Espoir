<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class OrganizationDashboardPreviewController extends AbstractController
    {
        #[Route(path: '/organization/dashboard/preview', name: 'organization_dashboard_preview')]
        #[IsGranted('ROLE_USER')]
        public function organizationDashboardPreview(): Response
        {
            return $this->render('user/employability/organizationManager/organizationDashboardPreview.html.twig');
        }

    }
<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Entity\JobOffers;
    use App\Entity\Organization;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class OrganizationDashboardPreviewController extends AbstractController
    {
        #[Route(path: '/organization/dashboard/preview/{id}', name: 'organization_dashboard_preview')]
        #[IsGranted('ROLE_ENT')]
        public function organizationDashboardPreview(Organization $organization): Response
        {
            $user = $this->getUser();
            if($organization->getUser() !== $user) {
                throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette organization');
            }

            return $this->render('user/employability/organizationManager/organizationDashboardPreview.html.twig', [
                'organization' => $organization,
            ]);
        }

    }
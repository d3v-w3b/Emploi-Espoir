<?php

    namespace App\Controller\Admin\OrganizationManager\RemovalRequest;

    use App\Entity\AccountDeletionRequest;
    use App\Entity\Admin;
    use App\Entity\Organization;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class OrganizationRemovalRequestListController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
        ){}



        #[Route(path: '/admin/organization/removal-request/list', name: 'admin_organization_removal_request_list')]
        #[IsGranted('ROLE_ADMIN')]
        public function organizationRemovalRequestList(): Response
        {
            $admin = $this->getUser();

            if (!$admin instanceof Admin) {
                $this->createAccessDeniedException('Cet espace est accessible uniquement aux administrateurs.');
            }

            $allRemovalRequests = $this->entityManager->getRepository(AccountDeletionRequest::class)->findBy(
                [],
                ['id' => 'DESC']
            );

            // Get organization info based on the applicant organization
            $organization = null;
            foreach ($allRemovalRequests as $request) {
                $organization = $this->entityManager->getRepository(Organization::class)->findOneBy([
                    'organizationName' => $request->getApplicantOrganization()
                ]);
            }

            return $this->render('admin/organizationManager/removalRequest/organizationRemovalRequestList.html.twig', [
                'all_removal_requests' => $allRemovalRequests,
                'organization' => $organization
            ]);
        }
    }
<?php

    namespace App\Controller\Admin\OrganizationManager\OrgChecking;

    use App\Entity\Organization;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class OrgListController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
        ){}



        #[Route(path: '/admin/org/list', name: 'admin_org_list')]
        #[IsGranted('ROLE_ADMIN')]
        public function orgList(): Response
        {
            $orgList = $this->entityManager->getRepository(Organization::class)->findBy(
                [],
            );

            return $this->render('admin/organizationManager/OrgChecking/orgList.html.twig', [
                'organization' => $orgList
            ]);
        }
    }
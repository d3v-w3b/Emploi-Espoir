<?php

    namespace App\Controller\Admin\OrganizationManager\OrgChecking;

    use App\Entity\Organization;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;
    use Knp\Component\Pager\PaginatorInterface;

    class OrgListController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly PaginatorInterface $paginator,
            private readonly RequestStack $requestStack
        ){}



        #[Route(path: '/admin/org/list', name: 'admin_org_list')]
        #[IsGranted('ROLE_ADMIN')]
        public function orgList(): Response
        {
            // This query builder allows to retrieve all organizations
            // and order by them by id
            $qb = $this->entityManager->getRepository(Organization::class)
                ->createQueryBuilder('o')
                ->orderBy('o.id', 'DESC');

            // Pagination
            $pagination = $this->paginator->paginate(
                $qb,
                $this->requestStack->getCurrentRequest()->query->getInt('page', 1),
                9
            );

            return $this->render('admin/organizationManager/OrgChecking/orgList.html.twig', [
                'organization' => $pagination
            ]);
        }
    }
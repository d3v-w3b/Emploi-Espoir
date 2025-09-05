<?php

    namespace App\Controller\Public;

    use App\Entity\JobOffers;
    use App\Form\Types\Public\Home\FilterJobOfferType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Knp\Component\Pager\PaginatorInterface;

    class HomeController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack,
            private readonly PaginatorInterface $paginator
        ){}


        #[Route(path: '/', name: 'home')]
        public function home(): Response
        {
            // default value if user does not exist
            $organizationEntity = null;

            // if an organization is associated to a user, put
            // organization name is the view
            $user = $this->getUser();
            if($user && method_exists($user, 'getOrganization')) {
                $organizationEntity = $user->getOrganization();
            }

            // Création du formulaire global
            $filterForm = $this->createForm(FilterJobOfferType::class, null, [
                'method' => 'GET',
            ]);
            $filterForm->handleRequest($this->requestStack->getCurrentRequest());

            // Création de la requête avec QueryBuilder
            $qb = $this->entityManager->getRepository(JobOffers::class)->createQueryBuilder('job')
                ->leftJoin('job.organization', 'org')
                ->orderBy('job.id', 'DESC')

                // Filter offers in progress
                ->andWhere('job.statu = true')
            ;

            //if ($filterForm->isSubmitted() && $filterForm->isValid()) {
                $data = $filterForm->getData();

                if (!empty($data['typeOfContract'])) {
                    $qb->andWhere('job.typeOfContract = :typeOfContract')
                        ->setParameter('typeOfContract', $data['typeOfContract'])
                    ;
                }

                if (!empty($data['organizationField'])) {
                    $qb->andWhere('org.sectorOfActivity LIKE :sector')
                        ->setParameter('sector', '%' . $data['organizationField'] . '%')
                    ;
                }
            //}



            // Pagination
            $pagination = $this->paginator->paginate(
                $qb,
                $this->requestStack->getCurrentRequest()->query->getInt('page', 1),
                32
            );

            return $this->render('public/home.html.twig', [
                'organization' => $organizationEntity,
                'job_offers' => $pagination,
                'filter_form' => $filterForm->createView(),
                'filter_data' => $this->requestStack->getCurrentRequest()->query->all()
            ]);
        }
    }
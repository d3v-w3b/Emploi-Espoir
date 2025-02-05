<?php

    namespace App\Controller\Public;

    use App\Entity\JobOffers;
    use App\Form\Fields\Public\Home\FilterByOrganizationFieldFields;
    use App\Form\Fields\Public\Home\FilterByTypeOfContractFields;
    use App\Form\Types\Public\Home\FilterByOrganizationFieldType;
    use App\Form\Types\Public\Home\FilterByTypeOfContractType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class HomeController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack
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

            // Filters offers by type of contract
            $filterByTypeOfContractFields = new FilterByTypeOfContractFields();

            $filterByTypeOfContractForm = $this->createForm(FilterByTypeOfContractType::class, $filterByTypeOfContractFields);
            $filterByTypeOfContractForm->handleRequest($this->requestStack->getCurrentRequest());

            // Filter by organization fields
            $filterByOrganizationFields = new FilterByOrganizationFieldFields();

            $filterByOrganizationForm = $this->createForm(FilterByOrganizationFieldType::class , $filterByOrganizationFields);
            $filterByOrganizationForm->handleRequest($this->requestStack->getCurrentRequest());

            // Création de la requête avec QueryBuilder
            $qb = $this->entityManager->getRepository(JobOffers::class)->createQueryBuilder('job')
                ->leftJoin('job.organization', 'org');


            if ($filterByTypeOfContractForm->isSubmitted() && $filterByTypeOfContractForm->isValid()) {
                $typeOfContract = $filterByTypeOfContractFields->getTypeOfContract();
                if ($typeOfContract) {
                    $qb->andWhere('job.typeOfContract = :typeOfContract')
                        ->setParameter('typeOfContract', $typeOfContract);
                }
            }

            if ($filterByOrganizationForm->isSubmitted() && $filterByOrganizationForm->isValid()) {
                $selectedSector = $filterByOrganizationFields->getOrganizationField();
                if ($selectedSector) {
                    $qb->andWhere('org.sectorOfActivity LIKE :sector')
                        ->setParameter('sector', '%'.$selectedSector.'%');
                }
            }

            // Exécuter la requête
            $jobOffers = $qb->getQuery()->getResult();


            // remove offer from view when expiration date === current date
            $validJobOffers = array_filter($jobOffers, function($jobOffer) {
                return $jobOffer->getExpirationDate()->format('Y-m-d') > (new \DateTimeImmutable())->format('Y-m-d');
            });

            return $this->render('public/home.html.twig', [
                'organization' => $organizationEntity,
                'job_offers' => $validJobOffers,
                'filter_by_type_of_contract' => $filterByTypeOfContractForm->createView(),
                'filter_by_organization_fields' => $filterByOrganizationForm->createView()
            ]);
        }
    }
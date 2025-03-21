<?php

    namespace App\Controller\User\UserPanel;

    use App\Entity\JobOffers;
    use App\Entity\Organization;
    use App\Entity\User;
    use App\Form\Types\Users\UserPanel\DashboardOfferFilterType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;
    use Knp\Component\Pager\PaginatorInterface;

    class DashboardController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack,
            private readonly PaginatorInterface $paginator
        ){}


        #[Route(path: '/dashboard', name: 'user_dashboard')]
        #[isGranted('ROLE_USER')]
        public function dashboard(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette page');
            }

            // Redirect to welcome page if main objectives or fields of interest
            // for current user logged is null
            $mainObjectives = $user->getMainObjectives();
            $fieldsOfInterest = $user->getFieldsOfInterest();

            if($mainObjectives === null || $fieldsOfInterest === null) { 
                return $this->redirectToRoute('welcome');
            }

            $userChoices = $user->getMainObjectives();

            $organizationPreferences = [];

            $allOrganizations = $this->entityManager->getRepository(Organization::class)->findAll();
            foreach ($allOrganizations as $organization) {
                $organizationObjectives = $organization->getOrganizationPreferences();

                // avoid that function array_diff() contain null values
                if($organizationObjectives !== null && $userChoices !== null) {

                    // array_intersect returns common elements between user choices and organization choices
                    if (!empty(array_intersect($userChoices, $organizationObjectives))) {
                        $organizationPreferences[] = $organization;
                    }
                }
            }

            // Display offers which related to the user's field
            $offerBasedOnUser = [];

            $allOffers = $this->entityManager->getRepository(JobOffers::class)->findAll();

            foreach ($allOffers as $offer) {
                // Get sectors of activities for each offer retrieve
                $offerSectorOfActivity = $offer->getOrganization()->getSectorOfActivity();

                if(!empty(array_intersect($fieldsOfInterest, $offerSectorOfActivity))) {
                    $offerBasedOnUser[] = $offer;
                }
            }

            // Filter offers based on type of contract
            $filterForm = $this->createForm(DashboardOfferFilterType::class);
            $filterForm->handleRequest($this->requestStack->getCurrentRequest());

            if($filterForm->isSubmitted() && $filterForm->isValid()) {
                $data = $filterForm->getData();

                if (!empty($data['typeOfContract'])) {
                    $offerBasedOnUser = array_filter($offerBasedOnUser, function ($offer) use ($data) {
                        return $offer->getTypeOfContract() === $data['typeOfContract'];
                    });
                }
            }

            // Pagination
            $pagination = $this->paginator->paginate(
                $offerBasedOnUser,
                $this->requestStack->getCurrentRequest()->query->getInt('page', 1),
                8
            );

            return $this->render('user/userPanel/dashboard.html.twig', [
                'user' => $user,
                'organizationPreferences' => $organizationPreferences,
                'offerBasedOnUser' => $pagination,
                'filterForm' => $filterForm->createView(),
            ]);
        }
    }
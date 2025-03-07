<?php

    namespace App\Controller\User\UserPanel;

    use App\Entity\Organization;
    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class DashboardController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
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

            return $this->render('user/userPanel/dashboard.html.twig', [
                'user' => $user,
                'organizationPreferences' => $organizationPreferences
            ]);
        }
    }
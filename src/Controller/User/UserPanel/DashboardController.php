<?php

    namespace App\Controller\User\UserPanel;

    use App\Entity\Organization;
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

            $userChoices = $user->getMainObjectives();

            $organizationPreferences = [];

            $allOrganizations = $this->entityManager->getRepository(Organization::class)->findAll();
            foreach ($allOrganizations as $organization) {
                $organizationObjectives = $organization->getOrganizationPreferences();

                // avoid that function array_diff() contain null values
                if($organizationObjectives !== null && $userChoices !== null) {
                    // a
                    if (!array_diff($userChoices, $organizationObjectives) && !array_diff($organizationObjectives, $userChoices)) {
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
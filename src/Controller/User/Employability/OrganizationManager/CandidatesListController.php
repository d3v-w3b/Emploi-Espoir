<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Entity\Organization;
    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class CandidatesListController extends AbstractController
    {
        private EntityManagerInterface $entityManager;


        public function __construct(EntityManagerInterface $entityManager)
        {
            $this->entityManager = $entityManager;
        }


        #[Route(path:  '/organization/candidates-list', name: 'organization_candidates_list')]
        #[IsGranted('ROLE_USER')]
        public function candidatesList(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            /**
             *
             * if ($currentUserMainObjectives) {
             * $allUsers = $this->entityManager->getRepository(User::class)->findAll();
             *
             * foreach ($allUsers as $candidate) {
             * // remove current user if it found in the result
             * if($candidate->getId() === $user->getId()) {
             * continue;
             * }
             *
             * $candidateObjectives = $candidate->getMainObjectives();
             *
             * foreach ($currentUserMainObjectives as $currentUserObjective) {
             * if (in_array($currentUserObjective, $candidateObjectives, true)) {
             * $usersWithSameObjectives[] = $candidate;
             * break;
             * }
             * }
             * }
             * }
             *
             * $currentUserMainObjectives = $user->getMainObjectives();
             * $usersWithSameObjectives = [];
             */
            // get current organizations preferences
            $organization = $this->entityManager->getRepository(Organization::class)->findOneBy([
                'organizationName' => $user->getOrganization()->getOrganizationName(),
            ]);

            $organizationPreferences = $organization->getOrganizationPreferences();

            $allUsers = $this->entityManager->getRepository(User::class)->findAll();

            return $this->render('user/employability/organizationManager/candidatesList.html.twig', [
                //'userWithSameObjectivesList' => $usersWithSameObjectives
                //'userMainObjectives' => $currentUserMainObjectives,
                //'otherUserWithSameObjectives' => $usersWithSameObjectives
                'organization' => $organization,
            ]);
        }
    }
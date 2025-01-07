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

            // get current organizations
            $organization = $this->entityManager->getRepository(Organization::class)->findOneBy([
                'organizationName' => $user->getOrganization()->getOrganizationName(),
            ]);

            // get current organization preferences
            $organizationPreferences = $organization->getOrganizationPreferences();

            $allUsers = $this->entityManager->getRepository(User::class)->findAll();

            $usersWithSameObjectives = [];
            foreach ($allUsers as $candidate) {
                $candidateObjectives = $candidate->getMainObjectives();

                // remove current user in the list if, this is in the list
                if($candidate->getId() === $user->getId()) {
                    continue;
                }

                /**
                 * $candidateObjectif manage an objectif of a user
                 *
                 * if a user has no objectif, foreach() contain a null value
                 * avoid this null value with the if
                 */
                if($candidateObjectives !== null) {
                    foreach ($candidateObjectives as $objective) {
                        if (in_array($objective, $organizationPreferences, true)) {
                            $usersWithSameObjectives[] = $candidate;
                            break; // Ajoute le candidat et sort de la boucle interne
                        }
                    }
                }
            }

            return $this->render('user/employability/organizationManager/candidatesList.html.twig', [
                'organization' => $organization,
                'userWithSameObjectives' => $usersWithSameObjectives,
            ]);
        }
    }
<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Entity\Organization;
    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class SpecificsProfilsListController extends AbstractController
    {
        private EntityManagerInterface $entityManager;


        public function __construct(EntityManagerInterface $entityManager)
        {
            $this->entityManager = $entityManager;
        }


        #[Route(path:  '/organization/specifics-profils-list', name: 'organization_specifics_profils_list')]
        #[IsGranted('ROLE_ENT')]
        public function specificsProfilsList(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            // get current organizations
            $organization = $user->getOrganization();

            $organizationSectorOfActivities = $organization->getSectorOfActivity();
            $allUsers = $this->entityManager->getRepository(User::class)->findAll();

            $specificsProfils = [];

            foreach ($allUsers as $user) {
                $userFieldsOfInterest = $user->getFieldsOfInterest();

                // avoid that function array_diff() contain null values//
                if($organizationSectorOfActivities !== null && $userFieldsOfInterest !== null) {

                    // array_intersect returns common elements between organization's sector of activity
                    // and user field of interest
                    if (!empty(array_intersect($userFieldsOfInterest, $organizationSectorOfActivities))) {
                        $specificsProfils[] = $user;
                    }
                }
            }


            return $this->render('user/employability/organizationManager/specificsProfilsList.html.twig', [
                'organization' => $organization,
                'specific_profil' => $specificsProfils
            ]);
        }
    }
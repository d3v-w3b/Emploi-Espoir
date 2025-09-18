<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Entity\Applicant;
    use App\Entity\Hiring;
    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class SpecificProfilDetails extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
        ){}


        #[Route(path: '/organization/specific-profil/details/{id}', name: 'organization_specific_profil_details')]
        #[IsGranted('ROLE_ENT')]
        public function specificProfilDetails(int $id): Response
        {
            // This user represents a user which is logged on the site
            // He not represents a user which apply or which has already applied to an offer
            $user = $this->entityManager->getRepository(User::class)->find($id);

            // This variable represents a user who has been already contacted by an organization
            $userApplicant = $this->entityManager->getRepository(Applicant::class)->findOneBy([
                'user' => $user
            ]);

            // This variable represents a userApplicant which is found in the Hiring entity
            $userHiring = $this->entityManager->getRepository(Hiring::class)->findOneBy([
                'applicant' => $userApplicant
            ]);

            $orgResponse = null;
            if ($userHiring) {
                $orgResponse = $userHiring->getOrganizationResponse();
            }

            return $this->render('user/employability/organizationManager/specificProfilDetails.html.twig', [
                'user' => $user,
                'applicant' => $userApplicant,
                'org_response' => $orgResponse
            ]);
        }
    }
<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Entity\Applicant;
    use App\Entity\Hiring;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class ApplicantDetailsController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
        ){}



        #[Route(path: '/organization/offer/applicant-details_{applicantId}', name: 'organization_offer_applicant_details')]
        #[IsGranted('ROLE_ENT')]
        public function applicantDetails(int $applicantId): Response
        {
            $applicant = $this->entityManager->getRepository(Applicant::class)->find($applicantId);

            // Get the current hiring for an offer from the applicant to display
            // it in a modal
            $applicantCurrentHiring = $this->entityManager->getRepository(Hiring::class)->findOneBy([
                'applicant' => $applicant
            ]);

            $orgResponse = null;
            if ($applicantCurrentHiring) {
                $orgResponse = $applicantCurrentHiring->getOrganizationResponse();
            }

            return $this->render('user/employability/organizationManager/applicantDetails.html.twig', [
                'applicant' => $applicant,
                'org_response' => $orgResponse,
            ]);
        }
    }
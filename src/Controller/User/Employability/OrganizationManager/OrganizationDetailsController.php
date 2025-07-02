<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Entity\Organization;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class OrganizationDetailsController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
        ){}



        #[Route(path: '/organization/{id}/offers', name: 'organization_offers')]
        #[IsGranted('ROLE_USER')]
        public function organizationDetails(int $id): Response
        {
            $organization = $this->entityManager->getRepository(Organization::class)->find($id);

            // Get the job offer oh the current org
            $jobOffers = $organization->getJobOffers();

            // Get applicant for each offer
            $applicants = null;
            foreach ($jobOffers as $offer) {
                $applicants = $offer->getApplicants();
            }

            return $this->render('user/employability/organizationManager/organizationDetails.html.twig', [
                'organization' => $organization,
                'job_offers' => $jobOffers,
                'applicants' => $applicants
            ]);
        }
    }
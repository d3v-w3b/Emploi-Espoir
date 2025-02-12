<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Entity\JobOffers;
    use App\Entity\Organization;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class MyOfferApplicationsListController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
        ){}


        #[Route(path: '/organization/{id}/offer/{offerId}/applications-list', name: 'offer_applications_list')]
        #[IsGranted('ROLE_USER')]
        public function myOfferApplicationList(Organization $organization, int $offerId): Response
        {
            $offer = $this->entityManager->getRepository(JobOffers::class)->find($offerId);

            // Get all applications associated to the current offer
            $applications = $offer->getApplicants();


            return $this->render('user/employability/organizationManager/myOfferApplicationList.html.twig', [
                'offer' => $offer,
                'applications' => $applications,
            ]);
        }
    }
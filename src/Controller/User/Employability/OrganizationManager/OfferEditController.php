<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Entity\JobOffers;
    use App\Entity\Organization;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\Finder\Exception\AccessDeniedException;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class OfferEditController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
        ){}


        #[Route(path: '/organization/{id}/myOffer/{offerId}/edit', name: 'organization_edit_offer')]
        #[IsGranted('ROLE_USER')]
        public function OfferEdit(Organization $organization, int $offerId): Response
        {
            // Check if this current organization belongs to the user logged
            $user = $this->getUser();
            if($organization->getUser() !== $user) {
                throw new AccessDeniedException('Vous n\'êtes pas autorisé à accéder à ces offres');
            }

            $jobOffer = $this->entityManager->getRepository(JobOffers::class)->find($offerId);

            return $this->render('user/employability/organizationManager/offerEdit.html.twig', [
                'job_offer' => $jobOffer,
            ]);
        }
    }
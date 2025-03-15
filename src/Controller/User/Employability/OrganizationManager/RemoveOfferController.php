<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Entity\JobOffers;
    use App\Entity\Organization;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class RemoveOfferController extends AbstractController
    {
        public function __construct(
            private readonly RequestStack $requestStack,
            private readonly EntityManagerInterface $entityManager
        ){}


        #[Route(path: '/organization/{id}/offer-remove/{offerId}', name: 'remove_offer', methods: ['POST'])]
        #[IsGranted('ROLE_ENT')]
        public function removeOffer(int $offerId, Organization $organization): RedirectResponse
        {
            if (!$this->isCsrfTokenValid('remove_offer_'.$offerId, $this->requestStack->getCurrentRequest()->get('_token'))) {
                $this->addFlash('CSRF_error', 'Token CSRF invalide.');
                return $this->redirectToRoute('organization_my_offers', [
                    'id' => $organization->getId(),
                ]);
            }

            $offer = $this->entityManager->getRepository(JobOffers::class)->find($offerId);

            if (!$offer) {
                $this->addFlash('offer_error', 'Offre introuvable.');
                return $this->redirectToRoute('organization_my_offers', [
                    'id' => $organization->getId(),
                ]);
            }

            $this->entityManager->remove($offer);
            $this->entityManager->flush();

            $this->addFlash('offer_remove_success', 'Offre supprimé avec succès');

            return $this->redirectToRoute('organization_my_offers', [
                'id' => $organization->getId(),
            ]);
        }
    }
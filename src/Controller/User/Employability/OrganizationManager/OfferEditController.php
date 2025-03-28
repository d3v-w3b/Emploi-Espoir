<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Entity\JobOffers;
    use App\Entity\Organization;
    use App\Form\Fields\Users\Employability\OrganizationManager\AddJobOfferFields;
    use App\Form\Types\Users\Employability\OrganizationManager\JobOfferEditType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\Finder\Exception\AccessDeniedException;
    use Symfony\Component\Form\FormError;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class OfferEditController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack
        ){}


        #[Route(path: '/organization/{id}/myOffer/{offerId}/edit', name: 'organization_edit_offer')]
        #[IsGranted('ROLE_ENT')]
        public function OfferEdit(Organization $organization, int $offerId): Response
        {
            // Check if this current organization belongs to the user logged
            $user = $this->getUser();
            if($organization->getUser() !== $user) {
                throw new AccessDeniedException('Vous n\'êtes pas autorisé à accéder à ces offres');
            }

            $jobOffer = $this->entityManager->getRepository(JobOffers::class)->find($offerId);

            $offerEditFields = new AddJobOfferFields();

            // Pre-filed fields for see current datas
            $offerEditFields->setJobTitle($jobOffer->getJobTitle());
            $offerEditFields->setTypeOfContract($jobOffer->getTypeOfContract());
            $offerEditFields->setWhatWeOffer(implode(', ', $jobOffer->getWhatWeOffer()));
            $offerEditFields->setMissions(implode(', ', $jobOffer->getMissions()));
            $offerEditFields->setOrganizationAbout($jobOffer->getOrganizationAbout());
            $offerEditFields->setTown($jobOffer->getTown());
            $offerEditFields->setJobPreferences($jobOffer->getJobPreferences());
            $offerEditFields->setDocsToProvide($jobOffer->getDocsToProvide());
            $offerEditFields->setExpirationDate($jobOffer->getExpirationDate());
            $offerEditFields->setProfilSought(implode(', ', $jobOffer->getProfilSought()));

            $offerEditForm = $this->createForm(JobOfferEditType::class, $offerEditFields);
            $offerEditForm->handleRequest($this->requestStack->getCurrentRequest());

            if($offerEditForm->isSubmitted() && $offerEditForm->isValid()) {
                $jobOffer->setTypeOfContract($offerEditFields->getTypeOfContract());
                $jobOffer->setJobPreferences($offerEditFields->getJobPreferences());
                $jobOffer->setMissions(explode(', ', $offerEditFields->getMissions()));
                $jobOffer->setProfilSought(explode(', ', $offerEditFields->getProfilSought()));
                $jobOffer->setExpirationDate($offerEditFields->getExpirationDate());

                $expirationDate = $offerEditFields->getExpirationDate()->format('Y-m-d');
                $currentDate = (new \DateTimeImmutable())->setTime(0, 0, 0)->format('Y-m-d');

                if ($expirationDate <= $currentDate) {
                    $jobOffer->setStatu(false); // ou setIsActive(false) si c'est ton champ
                }
                else {
                    $jobOffer->setStatu(true);
                }

                /**
                 *
                 * // Show error if expiration date is less than current date
                 * $expirationDate = $offerEditFields->getExpirationDate()->format('Y-m-d');
                 * $currentDate = (new \DateTimeImmutable())->setTime(0, 0, 0)->format('Y-m-d');
                 *
                 * // Date comparison
                 * if($expirationDate <= $currentDate) {
                 * $offerEditForm->get('expirationDate')->addError(new FormError('Entrez une date d\'expiration valide'));
                 *
                 * // If expiration date is greater than or equal to current date,
                 * // display error in the view
                 * return $this->render('user/employability/organizationManager/offerEdit.html.twig', [
                 * 'job_offer' => $jobOffer,
                 * 'offer_edit_form' => $offerEditForm->createView(),
                 * ]);
                 * }
                 *
                 */



                $this->entityManager->flush();

                $this->addFlash('offer_updating_success', 'Une offre vient d\'être modifié');

                return $this->redirectToRoute('organization_my_offers', [
                    'id' => $organization->getId(),
                ]);
            }



            return $this->render('user/employability/organizationManager/offerEdit.html.twig', [
                'job_offer' => $jobOffer,
                'offer_edit_form' => $offerEditForm->createView(),
            ]);
        }
    }
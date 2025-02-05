<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Entity\JobOffers;
    use App\Entity\Organization;
    use App\Form\Fields\Users\Employability\OrganizationManager\RemoveOfferFields;
    use App\Form\Types\Users\Employability\OrganizationManager\RemoveOfferType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Finder\Exception\AccessDeniedException;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class MyOffersController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
        ){}


        #[Route(path: '/organization/{id}/my-offers', name: 'organization_my_offers')]
        #[IsGranted('ROLE_USER')]
        public function myOffers(Organization $organization): Response
        {
            // Check if this current organization belongs to the user logged
            $user = $this->getUser();
            if($organization->getUser() !== $user) {
                throw new AccessDeniedException('Vous n\'êtes pas autorisé à accéder à ces offres');
            }

            $myOffers = $this->entityManager->getRepository(JobOffers::class)->findBy(
                ['organization' => $organization],
                ['dateOfPublication' => 'DESC']
            );

            $removeOfferFields = new RemoveOfferFields();
            $removeOfferForm = $this->createForm(RemoveOfferType::class, $removeOfferFields);

            return $this->render('user/employability/organizationManager/myOffers.html.twig', [
                'my_offers' => $myOffers,
                'organization' => $organization,
                'remove_offer_form' => $removeOfferForm->createView()
            ]);
        }
    }
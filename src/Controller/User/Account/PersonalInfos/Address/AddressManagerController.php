<?php

    namespace App\Controller\User\Account\PersonalInfos\Address;

    use App\Entity\User;
    use App\Form\Fields\Users\Account\PersonalInfos\Address\AddressManagerFields;
    use App\Form\Types\Users\Account\PersonalInfos\Address\AddressManagerType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class AddressManagerController extends AbstractController
    {
        public function __construct(
            private readonly RequestStack $requestStack,
            private readonly EntityManagerInterface $entityManager
        ){}


        #[Route(path: '/account/address-contact/address', name: 'account_address_contact_address')]
        #[IsGranted('ROLE_USER')]
        public function addressManager(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $addressManagerFields = new AddressManagerFields();

            $addressManagerForm = $this->createForm(AddressManagerType::class, $addressManagerFields);
            $addressManagerForm->handleRequest($this->requestStack->getCurrentRequest());

            if($addressManagerForm->isSubmitted() && $addressManagerForm->isValid()) {
                $user->setAddress($addressManagerFields->getAddress());

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->addFlash('address_saved', 'Information sauvegardée');

                return $this->redirectToRoute('account_address_contact');
            }

            return $this->render('user/account/personalInfos/addressContact/addressManager.html.twig', [
                'addressForm' => $addressManagerForm->createView()
            ]);
        }
    }
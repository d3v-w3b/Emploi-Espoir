<?php

    namespace App\Controller\User\Account\PersonalInfos\Address;

    use App\Entity\User;
    use App\Form\Fields\Users\Account\PersonalInfos\Address\PhoneNumberManagerFields;
    use App\Form\Types\Users\Account\PersonalInfos\Address\PhoneNumberManagerType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class PhoneNumberManagerController extends AbstractController
    {
        private RequestStack $requestStack;
        private EntityManagerInterface $entityManager;


        public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
        {
            $this->entityManager = $entityManager;
            $this->requestStack = $requestStack;
        }


        #[Route(path: '/account/address-contact/phone-number', name: 'account_address_contact_phone')]
        #[IsGranted('ROLE_USER')]
        public function phoneNumberManager(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $phoneManagerFields = new PhoneNumberManagerFields();

            // pre-file phone form is user's phone exist
            $phoneManagerFields->setPhone($user->getPhone());

            $phoneManagerType = $this->createForm(PhoneNumberManagerType::class, $phoneManagerFields);
            $phoneManagerType->handleRequest($this->requestStack->getCurrentRequest());

            if($phoneManagerType->isSubmitted() && $phoneManagerType->isValid()) {
                $user->setPhone($phoneManagerFields->getPhone());

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->addFlash('phone_number_saved', 'Information sauvegardé');

                return $this->redirectToRoute('account_address_contact');
            }

            return $this->render('user/account/personalInfos/addressContact/phoneNumberManager.html.twig', [
                'phone_manager_form' => $phoneManagerType->createView(),
            ]);
        }
    }
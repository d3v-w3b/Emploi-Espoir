<?php

    namespace App\Controller\User\Account\AddressContact;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class AddressContactController extends AbstractController
    {
        #[Route(path: '/account/address-contact', name: 'account_address_contact')]
        #[IsGranted('ROLE_USER')]
        public function addressContact(): Response
        {
            //get current user
            $user = $this->getUser();

            return $this->render('user/account/addressContact/addressContact.html.twig', [
                'user' => $user,
            ]);
        }
    }
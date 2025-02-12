<?php

    namespace App\Controller\User\Account\PersonalInfos\Address;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class AddressManagerController extends AbstractController
    {
        #[Route(path: '/account/address-contact/address', name: 'account_address_contact_address')]
        #[IsGranted('ROLE_USER')]
        public function addressManager(): Response
        {
            return $this->render('user/account/personalInfos/addressContact/Address.html.twig');
        }
    }
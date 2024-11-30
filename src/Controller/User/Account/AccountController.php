<?php

    namespace App\Controller\User\Account;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class AccountController extends AbstractController
    {
        #[Route(path: '/account', name: 'account')]
        #[IsGranted('ROLE_USER')]
        public function account(): Response
        {
            //get current user
            $user = $this->getUser();

            return $this->render('user/account/account.html.twig', [
                'user' => $user,
            ]);
        }
    }
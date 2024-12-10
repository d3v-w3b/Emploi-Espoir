<?php

    namespace App\Controller\User\Account\PersonalInfos\Identity;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class IdentityController extends AbstractController
    {
        #[Route(path: '/account/identity', name: 'account_identity')]
        #[IsGranted('ROLE_USER')]
        public function identity(): Response
        {
            //get current user
            $user = $this->getUser();

            return $this->render('user/account/personalInfos/identity/identity.html.twig', [
                'user' => $user,
            ]);
        }
    }
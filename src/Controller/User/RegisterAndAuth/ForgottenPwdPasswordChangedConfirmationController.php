<?php

    namespace App\Controller\User\RegisterAndAuth;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class ForgottenPwdPasswordChangedConfirmationController extends AbstractController
    {
        #[Route(path: '/forgotten-pwd/password-changed-confirmation', name: 'forgotten_pwd_password_changed_confirmation')]
        #[IsGranted('ROLE_USER')]
        public function forgottenPwdPasswordChangedConfirmation(): Response
        {
            return $this->render('user/registerAndAuth/forgottenPwdPasswordChangedConfirmation.html.twig');
        }
    }
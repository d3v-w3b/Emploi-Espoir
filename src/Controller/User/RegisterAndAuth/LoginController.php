<?php

    namespace App\Controller\User\RegisterAndAuth;
    
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

    class LoginController extends AbstractController
    {
        public function __construct(
            private readonly RequestStack $requestStack,
        ){}




        #[Route(path: '/login/password', name: 'user_login')]
        public function login(AuthenticationUtils $authenticationUtils): Response
        {
            if ($this->getUser()) {
                return $this->redirectToRoute('user_dashboard');
            }

            // get session
            $session = $this->requestStack->getSession();
            $emailEntered = $session->get('email_entered');

            if(!$emailEntered) {
                return $this->redirectToRoute('login_email');
            }

            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();

            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('user/registerAndAuth/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error,
                'emailEntered' => $emailEntered,
            ]);
        }


        #[Route(path: '/user_logout', name: 'user_logout')]
        public function logout(): void
        {
            throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
        }
    }

<?php

    namespace App\Controller\User\RegisterAndAuth;

    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
    use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

    class ForgottenPwdConfirmationRequest extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly TokenStorageInterface $tokenStorage,
        ){}



        #[Route(path: '/forgotten-pwd/confirmation-request/{token}', name: 'forgotten_pwd_request_confirmation')]
        public function forgottenPwdConfirmationRequest(string $token): Response
        {
            $user = $this->entityManager->getRepository(User::class)->findOneBy([
                'passwordChangeToken' => $token
            ]);

            if (!$user) {
                throw $this->createNotFoundException('Lien invalide ou expiré');
            }

            // Logged in the user based on his token
            $tokenForAuth = new UsernamePasswordToken($user, 'user', $user->getRoles());
            $this->tokenStorage->setToken($tokenForAuth);

            // remove the token after logged in the user
            $user->setPasswordChangeToken(null);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('forgotten_pwd_generate_new_pwd');
        }
    }
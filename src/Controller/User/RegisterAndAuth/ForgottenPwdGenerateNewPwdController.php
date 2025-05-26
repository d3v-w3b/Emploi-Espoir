<?php

    namespace App\Controller\User\RegisterAndAuth;

    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bridge\Twig\Mime\TemplatedEmail;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class ForgottenPwdGenerateNewPwdController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly MailerInterface $mailer,
            private readonly UserPasswordHasherInterface $passwordHasher,
            private readonly RequestStack $requestStack
        ){}



        #[Route(path: '/forgotten-pwd/generate-new-pwd', name: 'forgotten_pwd_generate_new_pwd', methods: ['POST', 'GET'])]
        #[IsGranted('ROLE_USER')]
        public function forgottenPwdGenerateNewPwd(): Response
        {
            $user = $this->getUser();

            if (!$user instanceof User) {
                $this->createAccessDeniedException('Vous n\'avez pas accès à cette page.');
            }

            // If the request is not a POST request, display the page's content
            if (!$this->requestStack->getCurrentRequest()->isMethod('POST')) {
                return $this->render('user/registerAndAuth/forgottenPwdGenerateNewPwd.html.twig');
            }

            // Generate a new password
            $newPassword = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 4, 16);
            $user->setPassword($this->passwordHasher->hashPassword($user, $newPassword));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            // Send email with the new password
            try {
                $email = (new TemplatedEmail())
                    ->from('EmploiEspoir-Admin@admin.fr')
                    ->to($user->getEmail())
                    ->subject('Vos nouveau identifiants sur Emploi Espoir')
                    ->htmlTemplate('user/registerAndAuth/emailNewPasswordForForgottenPwd.html.twig')
                    ->context([
                        'userFirstName' => $user->getFirstName(),
                        'userNewPassword' => $newPassword,
                        'userEmail' => $user->getEmail(),
                        'userSettingsUrl' => $this->generateUrl('user_profile_settings_edit', [], UrlGeneratorInterface::ABSOLUTE_URL),
                    ])
                ;
                $this->mailer->send($email);
            }
            catch (TransportExceptionInterface $e) {
                $this->addFlash('forgotten_pwd_error_sending_email', $e->getMessage());
            }

            // Redirect to the success page
            return $this->redirectToRoute('forgotten_pwd_password_changed_confirmation');
        }
    }
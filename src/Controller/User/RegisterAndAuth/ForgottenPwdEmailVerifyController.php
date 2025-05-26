<?php

    namespace App\Controller\User\RegisterAndAuth;

    use App\Entity\User;
    use App\Form\Fields\Users\RegisterAndAuth\ForgottenPwdEmailVerifyFields;
    use App\Form\Types\Users\RegisterAndAuth\ForgottenPwdEmailVerifyType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bridge\Twig\Mime\TemplatedEmail;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
    use Symfony\Component\Uid\Uuid;

    class ForgottenPwdEmailVerifyController extends AbstractController
    {
        public function __construct(
            private readonly RequestStack $requestStack,
            private readonly EntityManagerInterface $entityManager,
            private readonly MailerInterface $mailer,
        ){}



        #[Route(path: '/forgotten-pwd/email-verify', name: 'forgotten_pwd_email_verify')]
        public function forgottenPwdEmailVerify(): Response
        {
            $forgottenPwdEmailVerifyFields = new ForgottenPwdEmailVerifyFields();

            // Put the current email session in the form
            $session = $this->requestStack->getSession();
            $forgottenPwdEmailVerifyFields->setEmail($session->get('email_entered'));

            $forgottenPwdEmailVerifyForm = $this->createForm(ForgottenPwdEmailVerifyType::class, $forgottenPwdEmailVerifyFields);
            $forgottenPwdEmailVerifyForm->handleRequest($this->requestStack->getCurrentRequest());

            if ($forgottenPwdEmailVerifyForm->isSubmitted() && $forgottenPwdEmailVerifyForm->isValid()) {
                // Generate a confirmation token
                $token = Uuid::v4()->toRfc4122();

                $user = $this->entityManager->getRepository(User::class)->findOneBy([
                    'email' => $session->get('email_entered'),
                ]);

                if(!$user && !$user instanceof User) {
                    $this->createNotFoundException('Utilisateur inexistant');
                }

                // Save the token in the database
                $user->setPasswordChangeToken($token);
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                // Send confirmation email
                try {
                    $message = (new TemplatedEmail())
                        ->from('EmploiEspoir-Admin@admin.fr')
                        ->to($session->get('email_entered'))
                        ->subject('Votre demande de nouveau mot de passe')
                        ->htmlTemplate('user/registerAndAuth/emailConfirmationForForgottenPassword.html.twig')
                        ->context([
                            'userFirstName' => $user->getFirstName(),
                            'confirmation_url' => $this->generateUrl('forgotten_pwd_request_confirmation', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL),
                        ])
                    ;
                    $this->mailer->send($message);

                    // Flash message if the e-mail has been sent
                    $this->addFlash('email_verify_msg', 'E-mail envoyé');
                }
                catch (TransportExceptionInterface $e) {
                    $this->addFlash('forgotten_pwd_error_sending_email', $e->getMessage());
                }
            }

            return $this->render('user/registerAndAuth/forgottenPwdEmailVerify.html.twig', [
                'email_verify_form' => $forgottenPwdEmailVerifyForm->createView(),
            ]);
        }
    }
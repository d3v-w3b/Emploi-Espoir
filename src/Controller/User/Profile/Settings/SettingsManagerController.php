<?php

    namespace App\Controller\User\Profile\Settings;

    use App\Entity\User;
    use App\Form\Fields\Users\Profile\Settings\UpdateEmailFields;
    use App\Form\Types\Users\Profile\Settings\UpdateEmailType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\Form\FormError;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;
    use Symfony\Bridge\Twig\Mime\TemplatedEmail;

    class SettingsManagerController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack,
            private readonly MailerInterface $mailer
        ){}



        #[Route(path: '/user/profile/settings/edit', name: 'user_profile_settings_edit')]
        #[IsGranted('ROLE_USER')]
        public function settingsManager(): Response
        {
            $user = $this->getUser();

            if (!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $updateEmailFields = new UpdateEmailFields();
            $updateEmailFields->setCurrentEmail($user->getEmail());

            $updateEmailForm = $this->createForm(UpdateEmailType::class, $updateEmailFields);
            $updateEmailForm->handleRequest($this->requestStack->getCurrentRequest());

            if ($updateEmailForm->isSubmitted() && $updateEmailForm->isValid()) {

                $userWithEmail = $this->entityManager->getRepository(User::class)->findOneBy([
                    'email' => $updateEmailFields->getNewEmail()
                ]);

                if ($userWithEmail && $userWithEmail !== $user) {
                    $updateEmailForm->get('newEmail')->addError(new FormError('Cet email est déjà utilisé par un autre compte.'));
                }
                else {
                    // send confirmation email
                    try {
                        $message = (new TemplatedEmail())
                            ->from('EmploiEspoir-Admin@admin.fr')
                            ->to($updateEmailFields->getNewEmail())
                            ->subject('Confirmation de votre nouvelle adresse email')
                            ->htmlTemplate('user/profile/settings/emailConfirmationForNewEmail.html.twig')
                            ->context([
                                'userFirstName' => $user->getFirstName(),
                            ])
                        ;
                        $this->mailer->send($message);
                    }
                    catch (TransportExceptionInterface $e) {
                        $this->addFlash('update_email_confirmation_error_sending', $e->getMessage());
                    }

                    $this->addFlash('update_email_confirmation_new_email', 'Un email de confirmation vous à été envoyé');
                }
            }

            return $this->render('user/profile/settings/settingsManager.html.twig', [
                'update_email_form' => $updateEmailForm->createView(),
            ]);
        }
    }
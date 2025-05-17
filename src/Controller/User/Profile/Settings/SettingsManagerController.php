<?php

    namespace App\Controller\User\Profile\Settings;

    use App\Entity\User;
    use App\Form\Fields\Users\Profile\Settings\DeleteUserAccountFields;
    use App\Form\Fields\Users\Profile\Settings\UpdateEmailFields;
    use App\Form\Fields\Users\Profile\Settings\UpdatePasswordFields;
    use App\Form\Types\Users\Profile\Settings\DeleteUserAccountType;
    use App\Form\Types\Users\Profile\Settings\UpdateEmailType;
    use App\Form\Types\Users\Profile\Settings\UpdatePasswordType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\Form\FormError;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
    use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
    use Symfony\Component\Security\Http\Attribute\IsGranted;
    use Symfony\Bridge\Twig\Mime\TemplatedEmail;
    use Symfony\Component\Uid\Uuid;

    class SettingsManagerController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack,
            private readonly MailerInterface $mailer,
            private readonly UserPasswordHasherInterface $passwordHasher,
            private readonly TokenStorageInterface $tokenStorage,
        ){}



        #[Route(path: '/user/profile/settings/edit', name: 'user_profile_settings_edit')]
        #[IsGranted('ROLE_USER')]
        public function settingsManager(): Response
        {
            $user = $this->getUser();

            if (!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            // Fields for update email
            $updateEmailFields = new UpdateEmailFields();
            $updateEmailFields->setCurrentEmail($user->getEmail());

            // Fields for update password
            $updatePasswordFields = new UpdatePasswordFields();

            // Fields for a delete account
            $delAccountPasswordFields = new DeleteUserAccountFields();

            // Create a form for update email
            $updateEmailForm = $this->createForm(UpdateEmailType::class, $updateEmailFields);
            $updateEmailForm->handleRequest($this->requestStack->getCurrentRequest());

            // Create a form for update password
            $updatePasswordForm = $this->createForm(UpdatePasswordType::class, $updatePasswordFields);
            $updatePasswordForm->handleRequest($this->requestStack->getCurrentRequest());

            // Create a form for a delete account
            $deleteAccountPasswordForm = $this->createForm(DeleteUserAccountType::class, $delAccountPasswordFields);
            $deleteAccountPasswordForm->handleRequest($this->requestStack->getCurrentRequest());

            /**
             * Form validation for update email
             */
            if ($updateEmailForm->isSubmitted() && $updateEmailForm->isValid()) {

                $userWithEmail = $this->entityManager->getRepository(User::class)->findOneBy([
                    'email' => $updateEmailFields->getNewEmail()
                ]);

                if ($userWithEmail && $userWithEmail !== $user) {
                    $updateEmailForm->get('newEmail')->addError(new FormError('Cet email est déjà utilisé par un autre compte.'));
                }
                elseif ($updateEmailFields->getNewEmail() === $user->getEmail()) {
                    $updateEmailForm->get('newEmail')->addError(new FormError('Vous utilisez déjà cet email.'));
                }
                else {
                    // Generate a confirmation token
                    $token = Uuid::v4()->toRfc4122();

                    // Save pending email and token in database
                    $user->setEmailChangeToken($token);
                    $user->setPendingEmail($updateEmailFields->getNewEmail());

                    $this->entityManager->flush();

                    // send confirmation email
                    try {
                        $message = (new TemplatedEmail())
                            ->from('EmploiEspoir-Admin@admin.fr')
                            ->to($updateEmailFields->getNewEmail())
                            ->subject('Confirmation de votre nouvelle adresse email')
                            ->htmlTemplate('user/profile/settings/emailConfirmationForNewEmail.html.twig')
                            ->context([
                                'userFirstName' => $user->getFirstName(),
                                'confirmation_url' => $this->generateUrl('user_confirm_new_email', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL)
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


            /**
             * Form validation for update password
             */
            if ($updatePasswordForm->isSubmitted() && $updatePasswordForm->isValid()) {
                $updatePwd = true;

                // Checking the current password
                if (!$this->passwordHasher->isPasswordValid($user, $updatePasswordFields->getCurrentPassword())) {
                    $updatePasswordForm->get('currentPassword')->addError(new FormError('Mot de passe incorrect'));
                    $updatePwd = false;
                }

                if ($updatePasswordFields->getNewPassword() !== $updatePasswordFields->getConfirmNewPassword()) {
                    $updatePasswordForm->get('confirmNewPassword')->addError(new FormError('Les mots de passe ne correspondent pas'));
                    $updatePwd = false;
                }

                if ($updatePwd) {
                    $user->setPassword($this->passwordHasher->hashPassword($user, $updatePasswordFields->getNewPassword()));

                    $this->entityManager->persist($user);
                    $this->entityManager->flush();

                    $this->addFlash('update_password_successfully', 'Mot de passe modifié avec succès');

                    return $this->redirectToRoute('user_profile_settings_edit');
                }
            }


            /**
             * Form validation for delete the user account
             */
            if ($deleteAccountPasswordForm->isSubmitted() && $deleteAccountPasswordForm->isValid()) {

                if (!$this->passwordHasher->isPasswordValid($user, $delAccountPasswordFields->getPassword())) {
                    $deleteAccountPasswordForm->get('password')->addError(new FormError('Mot de passe incorrect, impossible de supprimer votre compte.'));
                }
                else {
                    // Manually log out the user
                    $this->tokenStorage->setToken(null);
                    $this->requestStack->getCurrentRequest()->getSession()->invalidate();

                    $this->entityManager->remove($user);
                    $this->entityManager->flush();

                    $this->addFlash('account_deletion_successfully', 'Désolé de vous voir partir...');

                    return $this->redirectToRoute('home');
                }
            }


            return $this->render('user/profile/settings/settingsManager.html.twig', [
                'update_email_form' => $updateEmailForm->createView(),
                'update_password_form' => $updatePasswordForm->createView(),
                'delete_account_form' => $deleteAccountPasswordForm->createView(),
            ]);
        }
    }
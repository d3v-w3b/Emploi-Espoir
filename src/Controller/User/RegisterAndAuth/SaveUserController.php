<?php

    namespace App\Controller\User\RegisterAndAuth;

    use App\Entity\User;
    use App\Form\Fields\Users\RegisterAndAuth\SaveUserFields;
    use App\Form\Types\Users\RegisterAndAuth\SaveUserTypes;
    use App\Security\UserAuthenticator;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
    use Symfony\Component\Security\Core\Exception\AuthenticationException;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
    use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
    use Symfony\Bridge\Twig\Mime\TemplatedEmail;

    class SaveUserController extends AbstractController
    {
        public function __construct(
            private readonly RequestStack $requestStack,
            private readonly EntityManagerInterface $entityManager,
            private readonly UserPasswordHasherInterface $passwordHasher,
            private readonly MailerInterface $mailer,
            private readonly UserAuthenticatorInterface $authenticator,
            private readonly UserAuthenticator $userAuthenticator,
        ){}


        #[Route(path: '/registration', name: 'user_registration')]
        public function saveUser(): Response
        {
            $session = $this->requestStack->getSession();
            $emailEntered = $session->get('email_entered');

            $userEntity = new User();

            $saveUserFields = new SaveUserFields();
            $saveUserFields->setEmail($emailEntered);

            $saveUserTypes = $this->createForm(SaveUserTypes::class, $saveUserFields);

            $request = $this->requestStack->getCurrentRequest();
            $saveUserTypes->handleRequest($request);

            if($saveUserTypes->isSubmitted() && $saveUserTypes->isValid()) {

                //generate a random password
                $passwordGeneration = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 4, 16);

                $userEntity->setEmail($emailEntered);
                $userEntity->setFirstName($saveUserFields->getFirstName());
                $userEntity->setLastName($saveUserFields->getLastName());
                $userEntity->setDateOfBirth($saveUserFields->getDateOfBirth());
                $userEntity->setPassword($this->passwordHasher->hashPassword($userEntity, $passwordGeneration));

                $this->entityManager->persist($userEntity);
                $this->entityManager->flush();

                //authenticate user
                try {
                    $this->authenticator->authenticateUser($userEntity, $this->userAuthenticator, $request);
                }
                catch (CustomUserMessageAuthenticationException) {
                    $this->addFlash('authentication_failed', 'Essayez de vous connectez à nouveau');
                }
                catch (AuthenticationException $e) {
                    $this->addFlash('authentication_error', $e->getMessage());
                }

                //getting email saved
                $userSaved =  $this->entityManager->getRepository(User::class)->findOneBy([
                    'email' => $emailEntered,
                ]);

                //if user has been saved sending email
                if($userSaved) {

                    try {
                        $message = (new TemplatedEmail())
                            ->from('EmploiEspoir-Admin@admin.fr')
                            ->to($emailEntered)
                            ->subject('CODE DE VERIFICATION')
                            ->htmlTemplate('emailVerification.html.twig')
                            ->context([
                                'password' => $passwordGeneration,
                                'userEmail' => $emailEntered,
                                'userLastName' => $userSaved->getLastName(),
                                'settingsUrl' => $this->generateUrl('user_profile_settings_edit', [], UrlGeneratorInterface::ABSOLUTE_URL)
                            ])
                        ;

                        $this->mailer->send($message);
                    }
                    catch (TransportExceptionInterface $e) {
                        $this->addFlash('error_sending', $e->getMessage());
                    }
                }

                return $this->redirectToRoute('welcome');
            }

            return $this->render('user/registerAndAuth/saveUser.html.twig', [
                'saveUserForm' => $saveUserTypes->createView(),
                'emailEntered' => $emailEntered,
            ]);
        }
    }
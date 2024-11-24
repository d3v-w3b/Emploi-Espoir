<?php

    namespace App\Controller\User\RegisterAndAuth;

    use App\Entity\User;
    use App\Form\Fields\Users\RegisterAndAuth\SaveUserFields;
    use App\Form\Types\Users\RegisterAndAuth\SaveUserTypes;
    use App\Security\UserAuthenticator;
    use Symfony\Component\Security\Core\Exception\AuthenticationException;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Mime\Email;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
    use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

    class SaveUserController extends AbstractController
    {
        private RequestStack $requestStack;
        private EntityManagerInterface $entityManager;
        private UserPasswordHasherInterface $passwordHasher;
        private MailerInterface $mailer;
        private UserAuthenticatorInterface $authenticator;
        private UserAuthenticator $userAuthenticator;


        public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer, UserAuthenticatorInterface $authenticator, UserAuthenticator $userAuthenticator)
        {
            $this->entityManager = $entityManager;
            $this->requestStack = $requestStack;
            $this->passwordHasher = $passwordHasher;
            $this->mailer = $mailer;
            $this->userAuthenticator = $userAuthenticator;
            $this->authenticator = $authenticator;
        }


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
                        $message = (new Email())
                            ->from('EmploiEspoir-Admin@admin.fr')
                            ->to($emailEntered)
                            ->subject('CODE DE VERIFICATION')
                            ->text('Votre mot de passe est : '.$passwordGeneration)
                        ;

                        $this->mailer->send($message);
                    }
                    catch (TransportExceptionInterface $e) {
                        $this->addFlash('error_sending', $e->getMessage());
                    }
                }

                return $this->redirectToRoute('user_dashboard');
            }

            return $this->render('user/registerAndAuth/saveUser.html.twig', [
                'saveUserForm' => $saveUserTypes->createView(),
                'emailEntered' => $emailEntered,
            ]);
        }
    }
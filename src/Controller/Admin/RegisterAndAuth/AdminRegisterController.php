<?php

    namespace App\Controller\Admin\RegisterAndAuth;

    use App\Entity\Admin;
    use App\Form\Fields\Admin\RegisterAndAuth\AdminRegisterFields;
    use App\Form\Types\Admin\RegisterAndAuth\AdminRegisterType;
    use App\Security\AdminAuthenticator;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Core\Exception\AuthenticationException;
    use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
    use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

    class AdminRegisterController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack,
            private readonly UserPasswordHasherInterface $passwordHasher,
            private readonly UserAuthenticatorInterface $authenticator,
            private readonly AdminAuthenticator $adminAuthenticator,
        ){}



        #[Route(path: '/E_E/backstage/register', name: 'admin_register')]
        public function adminRegister(): Response
        {
            $adminRegistrationFields = new AdminRegisterFields();
            $adminEntity = new Admin();

            $adminRegistrationForm = $this->createForm(AdminRegisterType::class, $adminRegistrationFields);

            $request = $this->requestStack->getCurrentRequest();
            $adminRegistrationForm->handleRequest($request);

            if ($adminRegistrationForm->isSubmitted() && $adminRegistrationForm->isValid()) {

                // Save admin
                $adminEntity->setAdminName($adminRegistrationFields->getAdminName());
                $adminEntity->setEmail($adminRegistrationFields->getEmail());
                $adminEntity->setPassword($this->passwordHasher->hashPassword($adminEntity, $adminRegistrationFields->getPassword()));

                $this->entityManager->persist($adminEntity);
                $this->entityManager->flush();

                // Authenticate admin
                try {
                    $this->authenticator->authenticateUser($adminEntity, $this->adminAuthenticator, $request);

                    return $this->redirectToRoute('admin_dashboard');
                }
                catch (CustomUserMessageAuthenticationException) {
                    $this->addFlash('authentication_failed', 'Essayez de vous connectez à nouveau');
                }
                catch (AuthenticationException $e) {
                    $this->addFlash('authentication_error', $e->getMessage());
                }
            }

            return $this->render('admin/registerAndAuth/adminRegistration.html.twig', [
                'admin_registration_form' => $adminRegistrationForm->createView(),
            ]);
        }
    }
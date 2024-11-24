<?php

    namespace App\Controller\User\RegisterAndAuth;

    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use App\Form\Fields\Users\RegisterAndAuth\EmailFields;
    use App\Form\Types\Users\RegisterAndAuth\EmailTypes;
    use Symfony\Component\HttpFoundation\RequestStack;

    class EmailLoginController extends AbstractController
    {
        private RequestStack $requestStack;
        private EntityManagerInterface $entityManager;


        public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
        {
            $this->requestStack = $requestStack;
            $this->entityManager = $entityManager;
        }


        #[Route(path: '/login', name: 'login_email')]
        public function emailLogin(): Response
        {
            if($this->getUser()) {
                return $this->redirectToRoute('user_dashboard');
            }

            $emailField = new EmailFields();

            $emailType = $this->createForm(EmailTypes::class, $emailField);

            $request = $this->requestStack->getCurrentRequest();
            $emailType->handleRequest($request);

            if($emailType->isSubmitted() && $emailType->isValid()) {

                $emailEntered = $this->entityManager->getRepository(User::class)->findOneBy([
                    'email' => $emailField->getEmail(),
                ]);

                $session = $this->requestStack->getSession();
                $session->set('email_entered', $emailField->getEmail());


                if($emailEntered) {
                    return $this->redirectToRoute('user_login');
                }
                else {
                    return $this->redirectToRoute('user_registration');
                }
            }

            return $this->render('user/registerAndAuth/emailLogin.html.twig', [
                'emailLoginForm' => $emailType->createView(),
            ]);
        }
    }
<?php

    namespace App\Controller\Public;

    use App\Entity\HelpCenter;
    use App\Entity\User;
    use App\Form\Fields\Public\HelpCenter\HelpCenterFields;
    use App\Form\Types\Public\HelpCenter\HelpCenterType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

    class HelpCenterController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack
        ){}



        #[Route(path: 'help-center', name: 'help_center')]
        public function helpCenter(): Response
        {
            $user = $this->getUser();

            $helpCenterEntity = new HelpCenter();

            $helpCenterFields = new HelpCenterFields();
            if ($user instanceof User) {
                $helpCenterFields->setEmail($user->getEmail() ?? '');
                $helpCenterFields->setLastName($user->getLastName() ?? '');
                $helpCenterFields->setPhone($user->getPhone() ?? '');
                $helpCenterFields->setFirstName($user->getFirstName() ?? '');
            }

            $helpCenterForm = $this->createForm(HelpCenterType::class, $helpCenterFields);
            $helpCenterForm->handleRequest($this->requestStack->getCurrentRequest());

            if ($helpCenterForm->isSubmitted() && $helpCenterForm->isValid()){
                // Connect entities
                $user->addHelpCenter($helpCenterEntity);
                $helpCenterEntity->setUser($user);

                $helpCenterEntity->setPhone($helpCenterFields->getPhone());
                $helpCenterEntity->setEmail($helpCenterFields->getEmail());
                $helpCenterEntity->setFirstName($helpCenterFields->getFirstName());
                $helpCenterEntity->setLastName($helpCenterFields->getLastName());
                $helpCenterEntity->setDescription($helpCenterFields->getDescription());

                // Screenshot's management
                $screenshotFile = $helpCenterFields->getScreenshot();

                if ($screenshotFile) {
                    $destination = $this->getParameter('public/helpCenter/screenshot');
                    $fileName = uniqid().'.'.$screenshotFile->guessExtension();
                    $screenshotFile->move($destination, $fileName);

                    $screenshot = $destination. '/' .$fileName;

                    $helpCenterEntity->setScreenshot($screenshot);
                }

                $this->entityManager->persist($helpCenterEntity);
                $this->entityManager->flush();

                $this->addFlash('help_center_msg', 'Message envoyé');

                return $this->redirectToRoute('home');
            }

            return $this->render('public/helpCenter.html.twig', [
                'help_center_form' => $helpCenterForm->createView(),
            ]);
        }
    }
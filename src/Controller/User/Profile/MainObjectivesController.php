<?php

    namespace App\Controller\User\Profile;

    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use App\Form\Fields\Users\Profile\MainObjectivesFields;
    use App\Form\Types\Users\Profile\MainObjectivesType;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class MainObjectivesController extends AbstractController
    {
        private RequestStack $requestStack;
        private EntityManagerInterface $entityManager;


        public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
        {
            $this->requestStack = $requestStack;
            $this->entityManager = $entityManager;
        }


        #[Route(path: '/welcome', name: 'welcome')]
        #[IsGranted('ROLE_USER')]
        public function mainObjectives(): Response
        {
            $objectifFields = new MainObjectivesFields();

            $objectifTypes = $this->createForm(MainObjectivesType::class, $objectifFields);

            $request = $this->requestStack->getCurrentRequest();
            $objectifTypes->handleRequest($request);

            if($objectifTypes->isSubmitted() && $objectifTypes->isValid()) {
                //get current user
                $user = $this->entityManager->getRepository(User::class)->findOneBy([
                    'email' => $this->getUser()->getUserIdentifier(),
                ]);

                if($user) {
                    $alternance = $objectifFields->getAlternance();
                    $job = $objectifFields->getJob();
                    $objectives = [];

                    //add objectives only if they are defined
                    if ($alternance) {
                        $objectives[] = $alternance;
                    }
                    if ($job) {
                        $objectives[] = $job;
                    }

                    $user->setMainObjectives($objectives);
                }

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                return $this->redirectToRoute('user_dashboard');
            }

            return $this->render('user/profile/mainObjectives.html.twig', [
                'mainObjectivesForm' => $objectifTypes->createView(),
            ]);
        }
    }
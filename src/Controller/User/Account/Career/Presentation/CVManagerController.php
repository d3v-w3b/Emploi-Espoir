<?php

    namespace App\Controller\User\Account\Career\Presentation;

    use App\Entity\Career;
    use App\Entity\User;
    use App\Form\Fields\Users\Account\Career\Presentation\CvManagerFields;
    use App\Form\Types\Users\Account\Career\Presentation\CvManagerType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class CVManagerController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack
        ){}


        #[Route(path: '/account/presentation/cv', name: 'account_presentation_cv')]
        #[IsGranted('ROLE_USER')]
        public function cvManager(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $cvManagerFields = new CvManagerFields();
            $cvEntity = $user->getCareer() ?? new Career();

            $cvManagerForm = $this->createForm(CvManagerType::class, $cvManagerFields);
            $cvManagerForm->handleRequest($this->requestStack->getCurrentRequest());

            if($cvManagerForm->isSubmitted() && $cvManagerForm->isValid()) {
                // connect entities
                $cvEntity->setUser($user);
                $user->setCareer($cvEntity);

                $cvFile = $cvManagerFields->getCv();

                // CV file manager
                $destination = $this->getParameter('user/career/presentation/cv');
                $fileName = uniqid(). '.' .$cvFile->guessExtension();

                $cv = $destination. '/' . $fileName;

                $cvEntity->setCv($cv);

                $this->entityManager->persist($cvEntity);
                $this->entityManager->flush();

                $this->addFlash('cv_saved', 'Information enregistrée');

                return $this->redirectToRoute('account_presentation');
            }

            return $this->render('user/account/career/presentation/cvManager.html.twig', [
                'cvForm' => $cvManagerForm->createView(),
            ]);
        }
    }
<?php

    namespace App\Controller\User\Account\Career\Presentation;

    use App\Entity\Career;
    use App\Entity\User;
    use App\Form\Fields\Users\Account\Career\Presentation\AboutMeFields;
    use App\Form\Types\Users\Account\Career\Presentation\AboutMeType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class AboutMeController extends AbstractController
    {
        public function __construct(
            private readonly RequestStack $requestStack,
            private readonly EntityManagerInterface $entityManager
        ){}


        #[Route(path: '/account/presentation/about-me', name: 'account_presentation_about_me')]
        #[IsGranted('ROLE_USER')]
        public function aboutMe(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $aboutMeEntity = $user->getCareer() ?? new Career();
            $aboutMeFields = new AboutMeFields();

            // Pre-file text area field with the current value of $aboutYou
            $aboutMeFields->setAboutMe($user->getCareer()?->getAboutYou() ?? '');

            $aboutMeForm = $this->createForm(AboutMeType::class, $aboutMeFields);
            $aboutMeForm->handleRequest($this->requestStack->getCurrentRequest());

            if($aboutMeForm->isSubmitted() && $aboutMeForm->isValid()) {
                // Connect entities
                $user->setCareer($aboutMeEntity);
                $aboutMeEntity->setUser($user);

                $aboutMeEntity->setAboutYou($aboutMeFields->getAboutMe());

                $this->entityManager->persist($aboutMeEntity);
                $this->entityManager->flush();
                
                $this->addFlash('about_me_added', 'Information enregistrée');

                return $this->redirectToRoute('account_presentation');
            }

            return $this->render('user/account/career/presentation/aboutMe.html.twig', [
                'aboutMeForm' => $aboutMeForm->createView(),
            ]);
        }
    }
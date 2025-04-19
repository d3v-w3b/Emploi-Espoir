<?php

    namespace App\Controller\User\Account\Career\ExternalLinks;

    use App\Entity\Career;
    use App\Entity\User;
    use App\Form\Fields\Users\Account\Career\ExternalLinks\ExternalLinksManagerFields;
    use App\Form\Types\Users\Account\Career\ExternalLinks\ExternalLinksManagerType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class ExternalLinksManagerController extends AbstractController
    {
        private EntityManagerInterface $entityManager;
        private RequestStack $requestStack;


        public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
        {
            $this->requestStack = $requestStack;
            $this->entityManager = $entityManager;
        }


        #[Route(path: '/account/external-link/link', name: 'account_external_links_link')]
        #[IsGranted('ROLE_USER')]
        public function externalLinksManager(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette page');
            }

            $externalLinkFields = new ExternalLinksManagerFields();
            $careerEntity = $user->getCareer() ?? new Career();

            $externalLinkType = $this->createForm(ExternalLinksManagerType::class, $externalLinkFields);

            $externalLinkType->handleRequest($this->requestStack->getCurrentRequest());

            if($externalLinkType->isSubmitted() && $externalLinkType->isValid()) {
                // connect entities
                $careerEntity->setUser($user);
                $user->setCareer($careerEntity);

                $careerEntity->setLinkedInUrl($externalLinkFields->getLinkedInUrl() ?? $user->getCareer()->getLinkedInUrl());
                $careerEntity->setGithubUrl($externalLinkFields->getGithubUrl() ?? $user->getCareer()->getGithubUrl());
                $careerEntity->setWebsiteUrl($externalLinkFields->getWebsiteUrl() ?? $user->getCareer()->getWebsiteUrl());

                $this->entityManager->persist($careerEntity);
                $this->entityManager->flush();

                // Make redirect to user profil if it from to user profile
                if($this->requestStack->getCurrentRequest()->query->get('redirect') === 'user_profile_view_as_recruiter') {
                    $this->addFlash('information_saved', 'Information sauvegardée');
                    return $this->redirectToRoute('user_profile_view_as_recruiter');
                }

                $this->addFlash('link_added_successfully', 'Information sauvegardée');

                return $this->redirectToRoute('account_external_links');
            }

            return $this->render('user/account/career/externalLinks/externalLinksManager.html.twig', [
                'external_link_form' => $externalLinkType->createView(),
            ]);
        }
    }
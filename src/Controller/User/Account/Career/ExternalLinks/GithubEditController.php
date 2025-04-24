<?php

    namespace App\Controller\User\Account\Career\ExternalLinks;

    use App\Entity\User;
    use App\Form\Fields\Users\Account\Career\ExternalLinks\ExternalLinkGithubEditManagerFields;
    use App\Form\Types\Users\Account\Career\ExternalLinks\ExternalLinkGithubEditManagerType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;
    use Symfony\Component\HttpFoundation\Response;

    class GithubEditController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack
        ){}



        #[Route(path: '/account/external-links/link/github/edit', name: 'account_external_links_github_edit')]
        #[IsGranted('ROLE_USER')]
        public function githubEdit(): Response
        {
            $user = $this->getUser();

            if (!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $githubEditFields = new ExternalLinkGithubEditManagerFields();

            $currentGithubUrl = $user->getCareer()->getGithubUrl();

            if ($currentGithubUrl) {
                $githubEditFields->setGithubUrl($currentGithubUrl);
            }

            $githubEditForm = $this->createForm(ExternalLinkGithubEditManagerType::class, $githubEditFields);
            $githubEditForm->handleRequest($this->requestStack->getCurrentRequest());

            if ($githubEditForm->isSubmitted() && $githubEditForm->isValid()) {

                $user->getCareer()->setGithubUrl($githubEditFields->getGithubUrl());

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->addFlash('link_added_successfully', 'Information sauvegardée');

                return $this->redirectToRoute('account_external_links');
            }

            return $this->render('user/account/career/externalLinks/githubEdit.html.twig', [
                'github_edit_form' => $githubEditForm->createView(),
            ]);
        }
    }
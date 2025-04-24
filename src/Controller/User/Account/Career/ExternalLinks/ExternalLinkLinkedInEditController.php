<?php

    namespace App\Controller\User\Account\Career\ExternalLinks;

    use App\Entity\User;
    use App\Form\Fields\Users\Account\Career\ExternalLinks\ExternalLinkLinkedInEditManagerFields;
    use App\Form\Types\Users\Account\Career\ExternalLinks\ExternalLinkLinkedInEditManagerType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class ExternalLinkLinkedInEditController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack
        ){}



        #[Route(path: '/account/external-links/link/linked-in/edit', name: 'account_external_links_linked_in_edit')]
        #[isGranted('ROLE_USER')]
        public function linkedInEdit(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $linkedInUrlFields = new ExternalLinkLinkedInEditManagerFields();

            $curentLinkedInUrl = $user->getCareer()->getLinkedInUrl();

            if ($curentLinkedInUrl) {
                $linkedInUrlFields->setLinkedInUrl($curentLinkedInUrl);
            }

            $linkedInUrlForm = $this->createForm(ExternalLinkLinkedInEditManagerType::class, $linkedInUrlFields);
            $linkedInUrlForm->handleRequest($this->requestStack->getCurrentRequest());

            if ($linkedInUrlForm->isSubmitted() && $linkedInUrlForm->isValid()) {

                $user->getCareer()->setLinkedInUrl($linkedInUrlFields->getLinkedInUrl());

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->addFlash('link_added_successfully', 'Information sauvegardée');

                return $this->redirectToRoute('account_external_links');
            }
            
            return $this->render('user/account/career/externalLinks/linkedInEdit.html.twig', [
                'linkedInUrlEditForm' => $linkedInUrlForm->createView(),
            ]);
        }
    }
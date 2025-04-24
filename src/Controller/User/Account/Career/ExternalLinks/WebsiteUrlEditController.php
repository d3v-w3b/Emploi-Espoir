<?php

    namespace App\Controller\User\Account\Career\ExternalLinks;

    use App\Entity\User;
    use App\Form\Fields\Users\Account\Career\ExternalLinks\WebsiteUrlEditManagerFields;
    use App\Form\Types\Users\Account\Career\ExternalLinks\WebsiteUrlEditManagerType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class WebsiteUrlEditController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack,
        ){}


        #[Route(path: '/account/external-links/link/website-url/edit', name: 'account_external_links_website_url_edit')]
        #[IsGranted('ROLE_USER')]
        public function websiteUrlEdit(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $websiteUrlFields = new WebsiteUrlEditManagerFields();
            $currentWebsiteUrl = $user->getCareer()->getWebsiteUrl();

            if ($currentWebsiteUrl) {
                $websiteUrlFields->setWebsiteUrl($currentWebsiteUrl);
            }

            $websiteUrlForm = $this->createForm(WebsiteUrlEditManagerType::class, $websiteUrlFields);
            $websiteUrlForm->handleRequest($this->requestStack->getCurrentRequest());

            if ($websiteUrlForm->isSubmitted() && $websiteUrlForm->isValid()) {

                $user->getCareer()->setWebsiteUrl($websiteUrlFields->getWebsiteUrl());

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->addFlash('link_added_successfully', 'Information sauvegardée');

                return $this->redirectToRoute('account_external_links');
            }

            return $this->render('user/account/career/externalLinks/websiteUrlEdit.html.twig', [
                'website_url_edit_form' => $websiteUrlForm->createView(),
            ]);
        }
    }
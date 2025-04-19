<?php

    namespace App\Controller\User\Account\Career\ExternalLinks;

    use App\Entity\User;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class ExternalLinksController extends AbstractController
    {
        #[Route(path: '/account/external-links', name: 'account_external_links')]
        #[IsGranted('ROLE_USER')]
        public function externalLinks(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $externalLinks = $user->getCareer();

            return $this->render('user/account/career/externalLinks/externalLinks.html.twig', [
                'externalLinks' => $externalLinks
            ]);
        }
    }
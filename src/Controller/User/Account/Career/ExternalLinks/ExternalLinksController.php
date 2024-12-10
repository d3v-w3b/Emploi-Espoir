<?php

    namespace App\Controller\User\Account\Career\ExternalLinks;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class ExternalLinksController extends AbstractController
    {
        #[Route(path: '/account/external-link', name: 'account_external_link')]
        #[IsGranted('ROLE_USER')]
        public function externalLinks(): Response
        {
            return $this->render('user/account/career/externalLinks/externalLinks.html.twig');
        }
    }
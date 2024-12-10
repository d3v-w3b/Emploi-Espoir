<?php

    namespace App\Controller\User\Account\Career\Formations;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class FormationsController extends AbstractController
    {
        #[Route(path: '/account/formations', name: 'account_formations')]
        #[IsGranted('ROLE_USER')]
        public function formations(): Response
        {
            return $this->render('user/account/career/formations/formations.html.twig');
        }
    }
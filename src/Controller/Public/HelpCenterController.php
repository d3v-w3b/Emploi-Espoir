<?php

    namespace App\Controller\Public;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

    class HelpCenterController extends AbstractController
    {
        #[Route(path: 'help-center', name: 'help_center')]
        public function helpCenter(): Response
        {
            return $this->render('public/helpCenter.html.twig');
        }
    }
<?php

    namespace App\Controller\Public\Employability;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class OrganizationHomeController extends AbstractController
    {
        #[Route(path: '/organization', name: 'organization')]
        public function homeOrganization(): Response
        {
            return $this->render('public/employability/OrganizationHome.html.twig');
        }
    }
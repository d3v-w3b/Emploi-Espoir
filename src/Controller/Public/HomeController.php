<?php

    namespace App\Controller\Public;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class HomeController extends AbstractController
    {
        #[Route(path: '/', name: 'home')]
        public function home(): Response
        {
            // default value if user does not exist
            $organizationEntity = null;

            // if an organization is associated to a user, put
            // organization name is the view
            $user = $this->getUser();
            if($user) {
                $organizationEntity = $user->getOrganization();
            }


            return $this->render('public/home.html.twig', [
                'organization' => $organizationEntity,
            ]);
        }
    }
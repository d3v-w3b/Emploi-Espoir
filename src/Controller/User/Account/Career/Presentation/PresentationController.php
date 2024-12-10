<?php

    namespace App\Controller\User\Account\Career\Presentation;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class PresentationController extends AbstractController
    {
        #[Route(path: '/account/presentation', name: 'account_presentation')]
        #[IsGranted('ROLE_USER')]
        public function presentation(): Response
        {
            $user = $this->getUser();

            //get career entity for current user logged
            $career = $user->getCareer();

            return $this->render('user/account/career/presentation/presentation.html.twig', [
                'career' => $career,
            ]);
        }
    }
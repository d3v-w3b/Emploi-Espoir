<?php

    namespace App\Controller\User\ProfileViewAsRecruiter;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class ProfileViewAsRecruiterController extends AbstractController
    {
        #[Route(path: '/profile-view-as-recruiter', name: 'user_profile_view_as_recruiter')]
        #[IsGranted('ROLE_USER')]
        public function profileViewAsRecruiter(): Response
        {
            return $this->render('user/profileViewAsRecruiter/profileViewAsRecruiter.html.twig');
        }
    }
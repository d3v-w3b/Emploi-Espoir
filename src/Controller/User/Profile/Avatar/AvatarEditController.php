<?php

    namespace App\Controller\User\Profile\Avatar;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class AvatarEditController extends AbstractController
    {
        #[Route(path: '/user/profile/edit/avatar', name: 'user_profile_edit_avatar')]
        #[IsGranted('ROLE_USER')]
        public function avatarEdit(): Response
        {
            return $this->render('user/profile/avatar/avatarEdit.html.twig');
        }
    }
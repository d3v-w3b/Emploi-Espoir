<?php

    namespace App\Controller\User\Profile\Settings;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class SettingsManagerController extends AbstractController
    {
        #[Route(path: '/user/profile/settings/edit', name: 'user_profile_settings_edit')]
        #[IsGranted('ROLE_USER')]
        public function settingsManager(): Response
        {
            return $this->render('user/profile/settings/settingsManager.html.twig');
        }
    }
<?php

    namespace App\Controller\User\Account\Career\LanguageLevel;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class LanguageLevelController extends AbstractController
    {
        #[Route(path: '/account/languages', name: 'account_languages')]
        #[IsGranted('ROLE_USER')]
        public function LanguageLevel(): Response
        {
            $user = $this->getUser();

            //get entity for
            $career = $user->getCareer();

            return $this->render('user/account/career/languages/languages.html.twig', [
                'career_language_level' => $career,
            ]);
        }
    }
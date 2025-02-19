<?php

    namespace App\Controller\User\Account\Career\Language;

    use App\Entity\User;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class LanguageController extends AbstractController
    {
        #[Route(path: '/account/languages', name: 'account_languages')]
        #[IsGranted('ROLE_USER')]
        public function LanguageLevel(): Response
        {
            return $this->render('user/account/career/language/language.html.twig');
        }
    }
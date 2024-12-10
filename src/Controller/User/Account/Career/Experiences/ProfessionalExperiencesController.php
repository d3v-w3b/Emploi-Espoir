<?php

    namespace App\Controller\User\Account\Career\Experiences;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class ProfessionalExperiencesController extends AbstractController
    {
        #[Route(path: '/account/professional-experiences', name: 'account_professional_experience')]
        #[IsGranted('ROLE_USER')]
        public function professionalExperience(): Response
        {
            return $this->render('user/account/career/experiences/professionalExperiences.html.twig');
        }
    }
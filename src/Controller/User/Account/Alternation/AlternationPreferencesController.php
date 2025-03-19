<?php

    namespace App\Controller\User\Account\Alternation;

    use App\Entity\User;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class AlternationPreferencesController extends AbstractController
    {
        #[Route(path: '/account/alternation-preferences', name: 'account_alternation_preferences')]
        #[IsGranted('ROLE_USER')]
        public function alternationPreferences(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette page');
            }

            //get entity jobAndAlternation for current logged user
            $jobAndAlternation = $user->getJobAndAlternation();

            return $this->render('user/account/alternation/alternationPreferences.html.twig', [
                'job_and_alternation' => $jobAndAlternation,
            ]);
        }
    }
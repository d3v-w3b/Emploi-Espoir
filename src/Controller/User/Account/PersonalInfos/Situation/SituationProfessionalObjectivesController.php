<?php

    namespace App\Controller\User\Account\PersonalInfos\Situation;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class SituationProfessionalObjectivesController extends AbstractController
    {
        #[Route(path: '/account/situation-professional-objectives', name: 'account_situation')]
        #[IsGranted('ROLE_USER')]
        public function situationProfessionalObjectives(): Response
        {
            //get current user
            $user = $this->getUser();

            return $this->render('user/account/personalInfos/situation/situationProfessionalObjectives.html.twig', [
                'user' => $user,
            ]);
        }
    }
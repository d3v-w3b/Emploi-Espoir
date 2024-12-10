<?php

    namespace App\Controller\User\Account\Job;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class JobPreferencesController extends AbstractController
    {
        #[Route(path: '/account/job-preference', name: 'account_job_preference')]
        #[IsGranted('ROLE_USER')]
        public function jobPreferences(): Response
        {
            $user = $this->getUser();

            //get entity jobAndAlternation for current logged user
            $jobAndAlternation = $user->getJobAndAlternation();

            return $this->render('user/account/job/jobPreferences.html.twig', [
                'job_preferences' => $jobAndAlternation,
            ]);
        }
    }
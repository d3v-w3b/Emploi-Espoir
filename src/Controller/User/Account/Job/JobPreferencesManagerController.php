<?php

    namespace App\Controller\User\Account\Job;

    use App\Entity\JobAndAlternation;
    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;
    use App\Form\Fields\Users\Account\Job\JobPreferencesManagerFields;
    use App\Form\Types\Users\Account\Job\JobPreferencesManagerType;

    class JobPreferencesManagerController extends AbstractController
    {
        private EntityManagerInterface $entityManager;
        private RequestStack $requestStack;


        public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
        {
            $this->entityManager = $entityManager;
            $this->requestStack = $requestStack;
        }


        #[Route(path: '/account/job-preference/preferences', name: 'account_job_preferences_preference')]
        #[IsGranted('ROLE_USER')]
        public function jobPreferenceManager(): Response
        {
            // get current user
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette page');
            }

            $jobPreferences = $user->getJobAndAlternation();

            $jobPreferencesFields = new JobPreferencesManagerFields();

            if($jobPreferences) {
                $jobPreferencesFields->setJobPreferences($user->getJobAndAlternation()->getEmploymentPreference());
            }

            $jobPreferenceType = $this->createForm(JobPreferencesManagerType::class, $jobPreferencesFields);

            $jobPreferenceType->handleRequest($this->requestStack->getCurrentRequest());

            if($jobPreferenceType->isSubmitted() && $jobPreferenceType->isValid()) {
                $jobPreferencesEntity = $user->getJobAndAlternation() ?? new JobAndAlternation();

                // connect entities
                $user->setJobAndAlternation($jobPreferencesEntity);
                $jobPreferencesEntity->setUser($user);

                // update preferences
                $jobPreferencesEntity->setEmploymentPreference($jobPreferencesFields->getJobPreferences());

                $this->entityManager->persist($jobPreferencesEntity);
                $this->entityManager->flush();

                // Make redirect to user profil if it from to user profile
                if($this->requestStack->getCurrentRequest()->query->get('redirect') === 'user_profile_view_as_recruiter') {
                    $this->addFlash('information_saved', 'Information sauvegardée');
                    return $this->redirectToRoute('user_profile_view_as_recruiter');
                }

                $this->addFlash('job_preference_saved', 'Informations sauvegardée');

                return $this->redirectToRoute('account_job_preference');
            }

            return $this->render('user/account/job/jobPreferencesManager.html.twig', [
                'job_preferences_form' => $jobPreferenceType->createView(),
            ]);
        }
    }
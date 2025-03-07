<?php

    namespace App\Controller\User\Account\Alternation;

    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;
    use App\Form\Fields\Users\Account\Alternation\AlternationPreferencesManagerFields;
    use App\Form\Types\Users\Account\Alternation\AlternationPreferencesManagerType;
    use App\Entity\JobAndAlternation;
    use Symfony\Component\HttpFoundation\RequestStack;

    class AlternationPreferencesManagerController extends AbstractController
    {
        private RequestStack $requestStack;
        private EntityManagerInterface $entityManager;


        public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
        {
            $this->requestStack = $requestStack;
            $this->entityManager = $entityManager;
        }


        #[Route(path: '/account/alternation-preferences/preferences', name: 'account_alternation_preferences_choices')]
        #[IsGranted('ROLE_USER')]
        public function alternationPreferenceManager(): Response
        {
            // get current user
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            // get alternations preferences if they exist
            $alternationPreferences = $user->getJobAndAlternation();

            $alternationPreferencesFields = new AlternationPreferencesManagerFields();

            if($alternationPreferences) {
                $alternationPreferencesFields->setAlternationPreferences($alternationPreferences->getAlternationPreference());
            }

            $alternationPreferencesType = $this->createForm(AlternationPreferencesManagerType::class, $alternationPreferencesFields);

            $alternationPreferencesType->handleRequest($this->requestStack->getCurrentRequest());

            if($alternationPreferencesType->isSubmitted() && $alternationPreferencesType->isValid()) {

                // check if user has already preferences
                $alternationEntity = $user->getJobAndAlternation() ?? new JobAndAlternation();

                // update preferences
                $alternationEntity->setAlternationPreference($alternationPreferencesFields->getAlternationPreferences());

                // connect entities
                $user->setJobAndAlternation($alternationEntity);
                $alternationEntity->setUser($user);

                $this->entityManager->persist($alternationEntity);
                $this->entityManager->flush();

                $this->addFlash('preferences_saved', 'Information sauvegardée');

                return $this->redirectToRoute('account_alternation_preferences');
            }

            return $this->render('user/account/alternation/alternationPreferencesManager.html.twig', [
                'alternation_preferences_form' => $alternationPreferencesType->createView(),
                'alternation_preferences' => $alternationPreferences
            ]);
        }
    }
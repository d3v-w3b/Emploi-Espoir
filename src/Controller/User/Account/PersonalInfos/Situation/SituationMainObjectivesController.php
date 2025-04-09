<?php

    namespace App\Controller\User\Account\PersonalInfos\Situation;

    use App\Entity\User;
    use App\Form\Fields\Users\Account\PersonalInfos\Situation\MainObjectivesFields;
    use App\Form\Types\Users\Account\PersonalInfos\Situation\MainObjectivesType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class SituationMainObjectivesController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack
        ){}


        #[Route(path: '/account/situation/main-objectives/edit', name: 'account_situation_main_objectives_edit')]
        #[IsGranted('ROLE_USER')]
        public function situationMainObjectives(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $mainObjectives = new MainObjectivesFields();
            $mainObjectives->setMainObjectives($user->getMainObjectives());

            $mainObjectivesForm = $this->createForm(MainObjectivesType::class, $mainObjectives);
            $mainObjectivesForm->handleRequest($this->requestStack->getCurrentRequest());

            if($mainObjectivesForm->isSubmitted() && $mainObjectivesForm->isValid()) {
                $user->setMainObjectives($mainObjectives->getMainObjectives());

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->addFlash('information_saved', 'Information sauvegardée');

                return $this->redirectToRoute('account_situation');
            }

            return $this->render('user/account/personalInfos/situation/situationMainObjectives.html.twig', [
                'main_objectives_form' => $mainObjectivesForm->createView(),
            ]);
        }
    }
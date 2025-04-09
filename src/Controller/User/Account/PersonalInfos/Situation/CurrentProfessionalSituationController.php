<?php

    namespace App\Controller\User\Account\PersonalInfos\Situation;

    use App\Entity\User;
    use App\Form\Fields\Users\Account\PersonalInfos\Situation\CurrentProfessionalSituationFields;
    use App\Form\Types\Users\Account\PersonalInfos\Situation\CurrentProfessionalSituationType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class CurrentProfessionalSituationController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack
        ){}


        #[Route(path: '/account/situation/current-professional-situation/edit', name: 'account_situation_current_professional_situation_edit')]
        #[IsGranted('ROLE_USER')]
        public function currentProfessionalSituation(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $currentProfessionalSituationFields = new CurrentProfessionalSituationFields();
            $currentProfessionalSituationFields->setCurrentProfessionalSituation($user->getCurrentProfessionalSituation());

            $currentProfessionalSituationForm = $this->createForm(CurrentProfessionalSituationType::class, $currentProfessionalSituationFields);
            $currentProfessionalSituationForm->handleRequest($this->requestStack->getCurrentRequest());

            if($currentProfessionalSituationForm->isSubmitted() && $currentProfessionalSituationForm->isValid()) {
                $user->setCurrentProfessionalSituation($currentProfessionalSituationFields->getCurrentProfessionalSituation());

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->addFlash('information_saved', 'Information sauvegardée');

                return $this->redirectToRoute('account_situation');
            }

            return $this->render('user/account/personalInfos/situation/currentProfessionalSituation.html.twig', [
                'current_professional_situation_form' => $currentProfessionalSituationForm->createView(),
            ]);
        }
    }
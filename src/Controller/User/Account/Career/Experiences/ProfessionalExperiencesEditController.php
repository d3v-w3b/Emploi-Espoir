<?php

    namespace App\Controller\User\Account\Career\Experiences;

    use App\Entity\Experiences;
    use App\Entity\User;
    use App\Form\Fields\Users\Account\Career\Experiences\ProfessionalExperiencesEditFields;
    use App\Form\Types\Users\Account\Career\Experiences\ProfessionalExperiencesEditType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class ProfessionalExperiencesEditController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack,
        ){}



        #[Route(path: '/account/professional-experiences/experiences/edit/{id}', name: 'account_professional_experience_edit')]
        #[IsGranted('ROLE_USER')]
        public function professionalExperienceEdit(int $id): Response
        {
            $user = $this->getUser();

            if (!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $currentExperiences = $this->entityManager->getRepository(Experiences::class)->find($id);

            $experienceFields = new ProfessionalExperiencesEditFields();

            // Prefile form fields with current values of $currentExperiences
            $experienceFields->setJobTitle($currentExperiences->getJobTitle());
            $experienceFields->setJobField($currentExperiences->getJobField());
            $experienceFields->setTown($currentExperiences->getTown());
            $experienceFields->setEnterpriseName($currentExperiences->getEnterpriseName());
            $experienceFields->setStartDate($currentExperiences->getStartDate());
            $experienceFields->setEndDate($currentExperiences->getEndDate());
            $experienceFields->setJobDescription($currentExperiences->getJobDescription());

            $experienceForm = $this->createForm(ProfessionalExperiencesEditType::class, $experienceFields);
            $experienceForm->handleRequest($this->requestStack->getCurrentRequest());

            if ($experienceForm->isSubmitted() && $experienceForm->isValid()) {

                $currentExperiences->setJobTitle($experienceFields->getJobTitle());
                $currentExperiences->setJobField($experienceFields->getJobField());
                $currentExperiences->setTown($experienceFields->getTown());
                $currentExperiences->setEnterpriseName($experienceFields->getEnterpriseName());
                $currentExperiences->setStartDate($experienceFields->getStartDate());
                $currentExperiences->setEndDate($experienceFields->getEndDate());
                $currentExperiences->setJobDescription($experienceFields->getJobDescription());

                $this->entityManager->persist($currentExperiences);
                $this->entityManager->flush();

                $this->addFlash('experiences_saved', 'Information sauvegardée');

                return $this->redirectToRoute('account_professional_experience');
            }

            return $this->render('user/account/career/experiences/professionalExperiencesEdit.html.twig', [
                'current_experiences' => $currentExperiences,
                'experience_edit_form' => $experienceForm->createView(),
            ]);
        }
    }
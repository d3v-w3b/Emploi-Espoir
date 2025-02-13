<?php

    namespace App\Controller\User\Account\Career\Skills;

    use App\Entity\Career;
    use App\Entity\User;
    use App\Form\Fields\Users\Account\Career\Skills\SkillsManagerFields;
    use App\Form\Types\Users\Account\Career\Skills\SkillsManagerType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class SkillsManagerController extends AbstractController
    {
        private RequestStack $requestStack;
        private EntityManagerInterface $entityManager;


        public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
        {
            $this->requestStack = $requestStack;
            $this->entityManager = $entityManager;
        }


        #[Route(path: '/account/skills/skills-manager', name: 'account_skills_manager')]
        #[IsGranted('ROLE_USER')]
        public function skillsManager(): Response
        {
            //get current user
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $skillsManagerFields = new SkillsManagerFields();
            $skillsEntity = $user->getCareer() ?? new Career();

            $skillsManagerType = $this->createForm(SkillsManagerType::class, $skillsManagerFields);

            $skillsManagerType->handleRequest($this->requestStack->getCurrentRequest());

            if($skillsManagerType->isSubmitted() && $skillsManagerType->isValid()) {
                $skillsEntity->setSkills($skillsManagerFields->getSkills());

                // connect entities
                $user->setCareer($skillsEntity);
                $skillsEntity->setUser($user);

                $this->entityManager->persist($skillsEntity);
                $this->entityManager->flush();

                $this->addFlash('skill_saved', 'Information sauvegardée');

                return $this->redirectToRoute('account_skills');
            }

            return $this->render('user/account/career/skills/skillsManager.html.twig', [
                'skills_manager_form' => $skillsManagerType->createView(),
            ]);
        }
    }
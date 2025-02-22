<?php

    namespace App\Controller\User\Account\Career\Skills;

    use App\Entity\User;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class SkillsController extends AbstractController
    {
        #[Route(path: '/account/skills', name: 'account_skills')]
        #[IsGranted('ROLE_USER')]
        public function skills(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette page');
            }

            //get career entity for current user logged
            $career = $user->getCareer();

            return $this->render('user/account/career/skills/skills.html.twig', [
                'career' => $career,
            ]);
        }
    }
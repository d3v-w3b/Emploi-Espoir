<?php

    namespace App\Controller\User\Account\Career\Experiences;

    use App\Entity\Experiences;
    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class ProfessionalExperiencesController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
        ){}


        #[Route(path: '/account/professional-experiences', name: 'account_professional_experience')]
        #[IsGranted('ROLE_USER')]
        public function professionalExperience(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $experiences = $this->entityManager->getRepository(Experiences::class)->findBy(
                [],
                ['id' => 'DESC']
            );

            return $this->render('user/account/career/experiences/professionalExperiences.html.twig', [
                'experiences' => $experiences
            ]);
        }
    }
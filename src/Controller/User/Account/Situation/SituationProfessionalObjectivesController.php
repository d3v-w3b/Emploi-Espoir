<?php

    namespace App\Controller\User\Account\Situation;

    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class SituationProfessionalObjectivesController extends AbstractController
    {
        private EntityManagerInterface $entityManager;


        public function __construct(EntityManagerInterface $entityManager)
        {
            $this->entityManager = $entityManager;
        }


        #[Route(path: '/account/situation-professional-objectives', name: 'account_situation')]
        #[IsGranted('ROLE_USER')]
        public function situationProfessionalObjectives(): Response
        {
            //get current user
            $user = $this->getUser();

            return $this->render('user/account/situation/situationProfessionalObjectives.html.twig', [
                'user' => $user,
            ]);
        }
    }
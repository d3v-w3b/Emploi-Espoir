<?php

    namespace App\Controller\User\Account\Career\Formations;

    use App\Entity\Formation;
    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class FormationsController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
        ){}



        #[Route(path: '/account/formations', name: 'account_formations')]
        #[IsGranted('ROLE_USER')]
        public function formations(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $formations = $this->entityManager->getRepository(Formation::class)->findBy(
                [],
                ['id' => 'DESC']
            );

            return $this->render('user/account/career/formations/formations.html.twig', [
                'formations' => $formations
            ]);
        }
    }
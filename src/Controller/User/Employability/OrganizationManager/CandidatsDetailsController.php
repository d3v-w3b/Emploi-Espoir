<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class CandidatsDetailsController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
        ){}


        #[Route(path: '/organization/candidats/{id}', name: 'organization_candidats_details')]
        #[IsGranted('ROLE_USER')]
        public function candidatsDetails(int $id): Response
        {
            $user = $this->entityManager->getRepository(User::class)->find($id);

            return $this->render('user/employability/organizationManager/candidatsDetails.html.twig', [
                'user' => $user,
            ]);
        }
    }
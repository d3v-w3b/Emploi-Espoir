<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class SpecificProfilDetails extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
        ){}


        #[Route(path: '/organization/specific-profil/{id}', name: 'organization_specific_profil_details')]
        #[IsGranted('ROLE_ENT')]
        public function specificProfilDetails(int $id): Response
        {
            $user = $this->entityManager->getRepository(User::class)->find($id);

            return $this->render('user/employability/organizationManager/specificProfilDetails.html.twig', [
                'user' => $user,
            ]);
        }
    }
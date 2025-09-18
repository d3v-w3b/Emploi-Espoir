<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class SpecificProfilContactedInfosController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
        ){}



        #[Route(path: '/organization/specific-profil/{id}/infos', name: 'specific_profil_infos')]
        #[IsGranted('ROLE_ENT')]
        public function specificProfilContactedInfos(int $id): Response
        {
            $specificProfil = $this->entityManager->getRepository(User::class)->find($id);

            return $this->render('user/employability/organizationManager/specificProfilContactedInfo.html.twig', [
                'specific_profil' => $specificProfil
            ]);
        }
    }
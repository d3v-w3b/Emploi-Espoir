<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Entity\Applicant;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class CandidatRecruitmentController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
        ){}



        #[Route(path: '/organization/candidate/recruitment/{id}', name: 'organization_candidate_recruitment')]
        #[IsGranted('ROLE_ENT')]
        public function candidatRecruitment(int $id): Response
        {
            $currentCandidat = $this->entityManager->getRepository(Applicant::class)->find($id);

            return $this->render('user/employability/organizationManager/candidateRecruitment.html.twig', [
                'current_candidate' => $currentCandidat
            ]);
        }
    }
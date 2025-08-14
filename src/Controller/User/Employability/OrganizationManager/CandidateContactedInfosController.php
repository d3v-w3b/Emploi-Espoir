<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Entity\Applicant;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class CandidateContactedInfosController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
        ){}



        #[Route(path: '/organization/candidate/infos/{id}', name: 'organization_candidate_infos')]
        #[IsGranted('ROLE_ENT')]
        public function candidateContactedInfosController(int $id): Response
        {
            $candidateContacted = $this->entityManager->getRepository(Applicant::class)->find($id);

            return $this->render('user/employability/organizationManager/candidateContactedInfos.html.twig', [
                'candidate_contacted' => $candidateContacted
            ]);
        }
    }
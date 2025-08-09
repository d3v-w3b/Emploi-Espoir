<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Entity\Applicant;
    use App\Entity\Hiring;
    use App\Entity\User;
    use App\Form\Fields\Users\Employability\OrganizationManager\HiringFields;
    use App\Form\Types\Users\Employability\OrganizationManager\HiringType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class HiringController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
        ){}



        #[Route(path: '/organization/candidate/hiring/{id}', name: 'organization_candidate_hiring')]
        #[IsGranted('ROLE_ENT')]
        public function hiring(int $id): Response
        {
            $user = $this->getUser();

            if (!$user instanceof User) {
                throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette page');
            }

            $currentCandidat = $this->entityManager->getRepository(Applicant::class)->find($id);

            $hiringEntity = new Hiring();
            $hiringFields = new HiringFields();

            $hiringFields->setOrgOwnerFirstName($user->getFirstName());
            $hiringFields->setOrgOwnerLastName($user->getLastName());
            $hiringFields->setOrgOwnerEmail($user->getEmail());
            $hiringFields->setOrgOwnerPhone($user->getPhone() ?? '');

            $hiringForm = $this->createForm(HiringType::class, $hiringFields);

            return $this->render('user/employability/organizationManager/hiring.html.twig', [
                'current_candidate' => $currentCandidat,
                'hiring_form' => $hiringForm->createView(),
            ]);
        }
    }
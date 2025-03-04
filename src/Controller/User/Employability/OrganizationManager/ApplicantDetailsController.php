<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Entity\Applicant;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class ApplicantDetailsController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
        ){}



        #[Route(path: '/organization/offer/applicant-details_{applicantId}', name: 'organization_offer_applicant_details')]
        #[IsGranted('ROLE_USER')]
        public function applicantDetails(int $applicantId): Response
        {
            $applicant = $this->entityManager->getRepository(Applicant::class)->find($applicantId);
            //$applicantCareer = $applicant->getUser()->getCareer();


            return $this->render('user/employability/organizationManager/applicantDetails.html.twig', [
                'applicant' => $applicant,
                //'applicantCareer' => $applicantCareer
            ]);
        }
    }
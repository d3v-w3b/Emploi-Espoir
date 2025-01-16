<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Form\Fields\Users\Employability\OrganizationManager\AddJobOfferFields;
    use App\Form\Types\Users\Employability\OrganizationManager\AddJobOfferType;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class AddJobOfferController extends AbstractController
    {
        #[Route(path: '/organization/add-job-offer', name: 'organization_add_job_offer')]
        #[IsGranted('ROLE_USER')]
        public function addJobOffer(): Response
        {
            $jobOfferFields = new AddJobOfferFields();

            $jobOfferForm = $this->createForm(AddJobOfferType::class, $jobOfferFields);

            return $this->render('user/employability/organizationManager/addJobOffer.html.twig', [
                'job_offer_form' => $jobOfferForm->createView(),
            ]);
        }
    }
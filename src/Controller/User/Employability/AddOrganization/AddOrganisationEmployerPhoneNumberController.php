<?php

    namespace App\Controller\User\Employability\AddOrganization;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class AddOrganisationEmployerPhoneNumberController extends AbstractController
    {
        #[Route(path: '/organization/employer-phone-number', name: 'organisation_employer_phone_number')]
        public function employerPhoneNumber(): Response
        {
            return $this->render('user/employability/addOrganization/addOrganizationEmployerPhoneNumber.html.twig');
        }

    }
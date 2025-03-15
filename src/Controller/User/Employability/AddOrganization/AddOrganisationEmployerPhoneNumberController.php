<?php

    namespace App\Controller\User\Employability\AddOrganization;

    use App\Entity\User;
    use App\Form\Types\Users\Employability\AddOrganization\OrganizationEmployerPhoneNumberType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use App\Form\Fields\Users\Employability\AddOrganization\OrganizationEmployerPhoneNumberFields;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class AddOrganisationEmployerPhoneNumberController extends AbstractController
    {
        private RequestStack $requestStack;
        private EntityManagerInterface $entityManager;


        public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
        {
            $this->requestStack = $requestStack;
            $this->entityManager = $entityManager;
        }


        #[Route(path: '/organization/employer-phone-number', name: 'organisation_employer_phone_number')]
        #[IsGranted('ROLE_USER')]
        public function employerPhoneNumber(): Response
        {
            $user = $this->getUser();

            if (!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur non valide.');
            }

            $organizationPhoneFields = new OrganizationEmployerPhoneNumberFields();
            $organizationPhoneFields->setPhone($user->getPhone());

            $organizationPhoneType = $this->createForm(OrganizationEmployerPhoneNumberType::class, $organizationPhoneFields);
            $organizationPhoneType->handleRequest($this->requestStack->getCurrentRequest());

            if($organizationPhoneType->isSubmitted() && $organizationPhoneType->isValid()) {
                $user->setPhone($organizationPhoneFields->getPhone());

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                return $this->redirectToRoute('organization_dashboard_preview', [
                    'id' => $user->getOrganization()->getId(),
                ]);
            }

            return $this->render('user/employability/addOrganization/addOrganizationEmployerPhoneNumber.html.twig', [
                'organization_phone_form' => $organizationPhoneType->createView(),
            ]);
        }

    }
<?php

    /*
     * This file allows sending a request for a removed
     * user account which binds to an organization.
     */

    namespace App\Controller\User\Profile\Settings;

    use App\Entity\AccountDeletionRequest;
    use App\Entity\User;
    use App\Form\Fields\Users\Profile\Settings\OrgAccountRemovalRequestFields;
    use App\Form\Types\Users\Profile\Settings\OrgAccountRemovalRequestType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class OrgAccountRemovalRequestController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack,
        ){}



        #[Route(path: '/user/remove-account-organization/request', name: 'user_remove_account_organization_request')]
        #[IsGranted('ROLE_USER')]
        public function orgAccountRemovalRequest(): Response
        {
            $user = $this->getUser();

            if (!$user instanceof User) {
                throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette page.');
            }

            // Get organization binds to the current user
            $organization = $user->getOrganization()->getOrganizationName();

            $accountDeletionRequestEntity = new AccountDeletionRequest();
            $accountRemovalFields = new OrgAccountRemovalRequestFields();

            $accountRemovalForm = $this->createForm(OrgAccountRemovalRequestType::class, $accountRemovalFields);
            $accountRemovalForm->handleRequest($this->requestStack->getCurrentRequest());

            if ($accountRemovalForm->isSubmitted() && $accountRemovalForm->isValid()) {

                $accountDeletionRequestEntity->setEmail($accountRemovalFields->getEmail());
                $accountDeletionRequestEntity->setStatu($accountRemovalFields->getStatu());
                $accountDeletionRequestEntity->setDescription($accountRemovalFields->getDescription());
                $accountDeletionRequestEntity->setTelephone($accountRemovalFields->getPhone());
                $accountDeletionRequestEntity->setApplicantEmail($user->getEmail());
                $accountDeletionRequestEntity->setApplicantOrganization($organization);

                $this->entityManager->persist($accountDeletionRequestEntity);
                $this->entityManager->flush();

                $this->addFlash('request_sent', 'Votre demande à été envoyé');

                return $this->redirectToRoute('user_profile_settings_edit');
            }

            return $this->render('user/profile/settings/orgAccountRemovalRequest.html.twig', [
                'account_removal_form' => $accountRemovalForm->createView(),
            ]);
        }
    }
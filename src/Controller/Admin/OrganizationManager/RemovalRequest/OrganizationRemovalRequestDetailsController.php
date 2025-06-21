<?php

    namespace App\Controller\Admin\OrganizationManager\RemovalRequest;

    use App\Entity\AccountDeletionRequest;
    use App\Entity\Admin;
    use App\Entity\Organization;
    use App\Entity\User;
    use App\Form\Fields\Admin\OrganizationManager\RemovalRequest\OrganizationRemovedRequestFields;
    use App\Form\Types\Admin\OrganizationManager\RemovalRequest\OrganizationRemovedRequestType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bridge\Twig\Mime\TemplatedEmail;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\FormError;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class OrganizationRemovalRequestDetailsController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack,
            private readonly UserPasswordHasherInterface $passwordHasher,
            private readonly MailerInterface $mailer
        ){}



        #[Route(path: '/admin/organization/removal-request/details-{id}', name: 'organization_removal_request_details')]
        #[IsGranted('ROLE_ADMIN')]
        public function organizationRemovalRequestDetails(int $id): Response
        {
            $admin = $this->getUser();

            if (!$admin instanceof Admin) {
                $this->createAccessDeniedException('Vous n\'aves pas accès à cet espace');
            }

            $currentOrg = $this->entityManager->getRepository(AccountDeletionRequest::class)->find($id);

            // Get the info from the current organization
            $organization = $this->entityManager->getRepository(Organization::class)->findOneBy([
                'organizationName' => $currentOrg->getApplicantOrganization(),
            ]);


            // Get the user which have the current org
            $currentUser = $this->entityManager->getRepository(User::class)->findOneBy([
                'email' => $currentOrg->getApplicantEmail(),
            ]);

            // Get the current organization binds to the current user
            $orgForCurrentUser = $currentUser->getOrganization();

            /**
             * Deletion form manager
             */

            $orgDeletionFields = new OrganizationRemovedRequestFields();

            $orgDeletionForm = $this->createForm(OrganizationRemovedRequestType::class, $orgDeletionFields);
            $orgDeletionForm->handleRequest($this->requestStack->getCurrentRequest());

            if ($orgDeletionForm->isSubmitted() && $orgDeletionForm->isValid()) {

                if ($this->passwordHasher->isPasswordValid($admin, $orgDeletionFields->getPassword())) {

                    $this->entityManager->remove($orgForCurrentUser);
                    $this->entityManager->remove($currentUser);

                    // Remove the current removal request
                    $this->entityManager->remove($currentOrg);

                    $this->entityManager->flush();

                    // Sent email to the organization
                    try {
                        $message = (new TemplatedEmail())
                            ->from('EmploiEspoir-Admin@admin.fr')
                            ->to($currentOrg->getEmail())
                            ->subject('SUPPRESSION DE COMPTE')
                            ->htmlTemplate('admin/organizationManager/removalRequest/emailConfirmationRemovedOrg.html.twig')
                            ->context([
                                'userFirstName' => $currentUser->getFirstName(),
                                'organizationName' => $orgForCurrentUser->getOrganizationName()
                            ])
                        ;

                        $this->mailer->send($message);
                    }
                    catch (TransportExceptionInterface $e) {
                        $this->addFlash('error_sending', $e->getMessage());
                    }

                    $this->addFlash('org_account_removed_successfully', 'Un compte organization vient d\'être retiré');

                    return $this->redirectToRoute('admin_organization_removal_request_list');
                }
                else {
                    $orgDeletionForm->get('password')->addError(new FormError('Mot de passe incorrect, suppression de compte impossible'));
                }
            }

            return $this->render('admin/organizationManager/removalRequest/organizationRemovalRequestDetails.html.twig', [
                'current_org' => $currentOrg,
                'organization' => $organization,
                'org_deletion_form' => $orgDeletionForm->createView(),
                'user_org' => $orgForCurrentUser->getOrganizationName()
            ]);
        }
    }
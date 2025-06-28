<?php

    namespace App\Controller\Admin\OrganizationManager\OrgChecking;

    use App\Entity\Admin;
    use App\Entity\Organization;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class RemoveOrgController extends AbstractController
    {
        public function __construct(
            private readonly RequestStack $requestStack,
            private readonly EntityManagerInterface $entityManager
        ){}



        #[Route(path: '/admin/org/remove/{id}', name: 'admin_org_remove', methods: ['POST'])]
        #[IsGranted('ROLE_ADMIN')]
        public function removeOrg(int $id): RedirectResponse
        {
            if (!$this->isCsrfTokenValid('admin_org_remove_'.$id, $this->requestStack->getCurrentRequest()->get('_token'))) {
                $this->addFlash('CSRF_error_msg ', 'Token CSRF invalide');

                return $this->redirectToRoute('admin_org_list');
            }

            $organization = $this->entityManager->getRepository(Organization::class)->find($id);

            if (!$organization) {
                $this->addFlash('missing_organization_msg', 'Organisation introuvable');

                return $this->redirectToRoute('admin_org_list');
            }

            // Get the current user owner to this Ent., and remove the ROLE_ENT from him
            $currentUser = $organization->getUser();

            $role = $currentUser->getRoles();
            if (in_array('ROLE_ENT', $role)) {
                $updatedRoles = array_filter($role, fn($role) => $role !== 'ROLE_ENT');
                $currentUser->setRoles(array_values($updatedRoles));
            }

            $this->entityManager->remove($organization);
            $this->entityManager->flush();

            $this->addFlash('org_removed_successfully', 'Une organisation vient d\'être retiré');

            return $this->redirectToRoute('admin_org_list');
        }
    }
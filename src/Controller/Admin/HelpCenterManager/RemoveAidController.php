<?php

    namespace App\Controller\Admin\HelpCenterManager;

    use App\Entity\HelpCenter;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class RemoveAidController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack
        ){}



        #[Route(path: '/admin/aid/remove/{id}', name: 'admin_remove_aid')]
        #[IsGranted('ROLE_ADMIN')]
        public function removeAid(int $id): RedirectResponse
        {
            if (!$this->isCsrfTokenValid('admin_remove_aid_'.$id, $this->requestStack->getCurrentRequest()->get('_token'))) {
                $this->addFlash('CSRF_error', 'Token CSRF invalide');

                return $this->redirectToRoute('admin_help_center_list');
            }

            $currentAid = $this->entityManager->getRepository(HelpCenter::class)->find($id);

            if (!$currentAid) {
                $this->addFlash('aid_doesnt_exists', 'Requête introuvable');

                return $this->redirectToRoute('admin_help_center_list');
            }

            $this->entityManager->remove($currentAid);
            $this->entityManager->flush();

            $this->addFlash('remove_aid_successfully', 'Une requête du centre d\'aide vient d\'être retiré');

            return $this->redirectToRoute('admin_help_center_list');
        }
    }
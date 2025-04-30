<?php

    namespace App\Controller\User\Account\Career\Formations;

    use App\Entity\Formation;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class RemoveFormationController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack,
        ){}



        #[Route(path: '/account/formations/formation/remove/{id}', name: 'account_formation_remove', methods: ['POST'])]
        #[IsGranted('ROLE_USER')]
        public function removeFormation(int $id): RedirectResponse
        {
            if (!$this->isCsrfTokenValid('account_formation_remove_'.$id, $this->requestStack->getCurrentRequest()->get('_token'))) {
                $this->addFlash('CSRF_error', 'Token CSRF invalide');
                return $this->redirectToRoute('account_formation_edit', [
                    'id' => $id
                ]);
            }

            $currentFormation = $this->entityManager->getRepository(Formation::class)->find($id);

            if (!$currentFormation) {
                $this->addFlash('formation_missing', 'Formation inexistante');
                return $this->redirectToRoute('account_formations');
            }

            $this->entityManager->remove($currentFormation);
            $this->entityManager->flush();

            $this->addFlash('formation_removed_successfully', 'Formation supprimé avec succès');

            return $this->redirectToRoute('account_formations');
        }
    }
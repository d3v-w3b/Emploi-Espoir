<?php

    namespace App\Controller\User\Account\Career\Experiences;

    use App\Entity\Experiences;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;
    use Symfony\Component\HttpFoundation\RedirectResponse;


    class RemoveProfessionalExperienceController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack,
        ){}



        #[Route(path: '/account/professional-experiences/experience/remove/{id}', name: 'account_professional_experience_remove', methods: ['POST'])]
        #[IsGranted('ROLE_USER')]
        public function removeProfessionalExperience(int $id): RedirectResponse
        {
            if (!$this->isCsrfTokenValid('account_professional_experience_remove_'.$id, $this->requestStack->getCurrentRequest()->get('_token'))) {
                $this->addFlash('CSRF_error', 'Token CSRF invalide');
                return $this->redirectToRoute('account_professional_experience_edit', [
                    'id' => $id
                ]);
            }

            $currentExperience = $this->entityManager->getRepository(Experiences::class)->find($id);

            if (!$currentExperience) {
                $this->addFlash('experience_missing', 'Expérience inexistante');
                return $this->redirectToRoute('account_professional_experience_edit', [
                    'id' => $id
                ]);
            }

            $this->entityManager->remove($currentExperience);
            $this->entityManager->flush();

            $this->addFlash('experience_remove_success', 'Expérience supprimé avec succès');

            return $this->redirectToRoute('account_professional_experience');
        }
    }
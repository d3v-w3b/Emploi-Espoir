<?php

    namespace App\Controller\User\Account\Career\Language;

    use App\Entity\Language;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;
    use Symfony\Component\HttpFoundation\RedirectResponse;

    class RemoveLanguageController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack,
        ){}


        #[Route(path: '/account/languages/language-level/remove/{id}', name: 'account_remove_language', methods: ['POST'])]
        #[IsGranted('ROLE_USER')]
        public function removeLanguage(int $id): RedirectResponse
        {
            if (!$this->isCsrfTokenValid('account_remove_language_'.$id, $this->requestStack->getCurrentRequest()->get('_token'))) {
                $this->addFlash('CSRF_error', 'Token CSRF invalide');
                return $this->redirectToRoute('account_languages_language_level_edit', [
                    'id' => $id
                ]);
            }

            $currentLanguageLevel = $this->entityManager->getRepository(Language::class)->find($id);

            if (!$currentLanguageLevel) {
                $this->addFlash('language_missing', 'Langue inexistante');
                return $this->redirectToRoute('account_languages');
            }

            $this->entityManager->remove($currentLanguageLevel);
            $this->entityManager->flush();

            $this->addFlash('language_removed_successfully', 'Un niveau de langue vient d\'être supprimé');

            return $this->redirectToRoute('account_languages');
        }
    }
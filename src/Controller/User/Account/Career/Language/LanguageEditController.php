<?php

    namespace App\Controller\User\Account\Career\Language;

    use App\Entity\Language;
    use App\Entity\User;
    use App\Form\Fields\Users\Account\Career\Language\LanguageLevelEditFields;
    use App\Form\Types\Users\Account\Career\Language\LanguageLevelEditType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class LanguageEditController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack
        ){}



        #[Route(path: '/account/languages/language-level/edit/{id}', name: 'account_languages_language_level_edit')]
        #[IsGranted('ROLE_USER')]
        public function languageEdit(int $id): Response
        {
            $user = $this->getUser();

            if (!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $currentLanguage = $this->entityManager->getRepository(Language::class)->find($id);

            $languageEditFields = new LanguageLevelEditFields();
            $languageEditFields->setLanguageLevel($currentLanguage->getLanguageLevel());;

            $languageEditForm = $this->createForm(LanguageLevelEditType::class, $languageEditFields);
            $languageEditForm->handleRequest($this->requestStack->getCurrentRequest());

            if ($languageEditForm->isSubmitted() && $languageEditForm->isValid()) {
                // connect entities
                $currentLanguage->setUser($user);
                $user->addLanguage($currentLanguage);

                $currentLanguage->setLanguageLevel($languageEditFields->getLanguageLevel());

                $this->entityManager->persist($currentLanguage);
                $this->entityManager->flush();

                // Make redirect to user profil if it from to user profile
                if ($this->requestStack->getCurrentRequest()->query->get('redirect') === 'user_profile_view_as_recruiter') {
                    $this->addFlash('information_saved', 'Information sauvegardée');
                    return $this->redirectToRoute('user_profile_view_as_recruiter');
                }

                $this->addFlash('languageSaved', 'Information sauvegardée');

                return $this->redirectToRoute('account_languages');
            }

            return $this->render('user/account/career/language/languageEdit.html.twig', [
                'current_language' => $currentLanguage,
                'language_edit_form' => $languageEditForm->createView(),
            ]);
        }
    }
<?php

    namespace App\Controller\User\Account\Career\Language;

    use App\Entity\Language;
    use App\Entity\User;
    use App\Form\Fields\Users\Account\Career\Language\LanguageLevelFields;
    use App\Form\Types\Users\Account\Career\Language\LanguageLevelType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class LanguageManagerController extends AbstractController
    {
        public function __construct(
            private readonly RequestStack $requestStack,
            private readonly EntityManagerInterface $entityManager
        ){}


        #[Route(path: '/account/languages/language-level', name: 'account_languages_language_level')]
        #[IsGranted('ROLE_USER')]
        public function languageManager(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette page');
            }

            $languageEntity = new Language();

            $languageFields = new LanguageLevelFields();

            $languageForm = $this->createForm(LanguageLevelType::class, $languageFields);
            $languageForm->handleRequest($this->requestStack->getCurrentRequest());

            if($languageForm->isSubmitted() && $languageForm->isValid()) {
                // connect entities
                $user->addLanguage($languageEntity);
                $languageEntity->setUser($user);

                $languageEntity->setLanguage($languageFields->getLanguage());
                $languageEntity->setLanguageLevel($languageFields->getLanguageLevel());

                $this->entityManager->persist($languageEntity);
                $this->entityManager->flush();

                $this->addFlash('languageSaved', 'Information sauvegardée');

                return $this->redirectToRoute('account_languages');
            }

            return $this->render('user/account/career/language/languageManager.html.twig', [
                'language_form' => $languageForm->createView()
            ]);
        }
    }
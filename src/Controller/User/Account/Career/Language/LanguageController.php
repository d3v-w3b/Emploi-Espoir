<?php

    namespace App\Controller\User\Account\Career\Language;

    use App\Entity\Language;
    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class LanguageController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
        ){}


        #[Route(path: '/account/languages', name: 'account_languages')]
        #[IsGranted('ROLE_USER')]
        public function LanguageLevel(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $languages = $this->entityManager->getRepository(Language::class)->findBy(
                [],
                ['id' => 'DESC']
            );

            return $this->render('user/account/career/language/language.html.twig', [
                'languages' => $languages
            ]);
        }
    }
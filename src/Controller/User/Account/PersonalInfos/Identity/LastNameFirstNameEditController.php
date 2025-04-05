<?php

    namespace App\Controller\User\Account\PersonalInfos\Identity;

    use App\Entity\User;
    use App\Form\Fields\Users\Account\PersonalInfos\Identity\LastNameFirstNameEditFields;
    use App\Form\Types\Users\Account\PersonalInfos\Identity\LastNameFirstNameEditType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class LastNameFirstNameEditController extends AbstractController
    {
        public function __construct(
            private readonly RequestStack $requestStacks,
            private readonly EntityManagerInterface $entityManager
        ){}



        #[Route(path: '/account/identity/last-first-name/edit', name: 'account_last_first_name_edit')]
        #[IsGranted('ROLE_USER')]
        public function lastNameFirstNameEdit(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $lastFirstNameFields = new LastNameFirstNameEditFields();

            // Prefilled fields if values exist
            $lastFirstNameFields->setLastName($user->getLastName() ?? '');
            $lastFirstNameFields->setFirstName($user->getFirstName() ?? '');

            $lastFirstNameForm = $this->createForm(LastNameFirstNameEditType::class, $lastFirstNameFields);
            $lastFirstNameForm->handleRequest($this->requestStacks->getCurrentRequest());

            if($lastFirstNameForm->isSubmitted() && $lastFirstNameForm->isValid()) {
                $user->setFirstName($lastFirstNameFields->getFirstName());
                $user->setLastName($lastFirstNameFields->getLastName());

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->addFlash('information_saved', 'Information sauvegardée');

                return $this->redirectToRoute('account_identity');
            }


            return $this->render('user/account/personalInfos/identity/lastNameFirstNameEdit.html.twig', [
                'last_first_name_form' => $lastFirstNameForm->createView(),
            ]);
        }
    }
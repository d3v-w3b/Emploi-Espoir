<?php

    namespace App\Controller\User\Account\PersonalInfos\Identity;

    use App\Entity\User;
    use App\Form\Fields\Users\Account\PersonalInfos\Identity\DateOfBirthEditFields;
    use App\Form\Types\Users\Account\PersonalInfos\Identity\DateOfBirthEditType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class DateOfBirthEditController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack
        ){}


        #[Route(path: '/account/identity/birthday/edit', name: 'account_identity_birthday_edit')]
        #[IsGranted('ROLE_USER')]
        public function dateOfBirthEdit(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $dateOfBirthFields = new DateOfBirthEditFields();
            $dateOfBirthFields->setDateOfBirth($user->getDateOfBirth() ?? '');

            $dateOfBirthForm = $this->createForm(DateOfBirthEditType::class, $dateOfBirthFields);
            $dateOfBirthForm->handleRequest($this->requestStack->getCurrentRequest());

            if($dateOfBirthForm->isSubmitted() && $dateOfBirthForm->isValid()) {
                $user->setDateOfBirth($dateOfBirthFields->getDateOfBirth());

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->addFlash('information_saved', 'Information sauvegardée');

                return $this->redirectToRoute('account_identity');
            }

            return $this->render('user/account/personalInfos/identity/dateOfBirthEdit.html.twig', [
                'date_of_birth_form' => $dateOfBirthForm->createView(),
            ]);
        }
    }
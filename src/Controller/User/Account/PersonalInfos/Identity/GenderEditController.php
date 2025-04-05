<?php

    namespace App\Controller\User\Account\PersonalInfos\Identity;

    use App\Entity\User;
    use App\Form\Fields\Users\Account\PersonalInfos\Identity\GenderEditFields;
    use App\Form\Types\Users\Account\PersonalInfos\Identity\GenderEditType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class GenderEditController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack
        ){}



        #[Route(path: '/account/identity/gender/edit', name: 'account_identity_gender_edit')]
        #[IsGranted('ROLE_USER')]
        public function genderEdit(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $genderEditFields = new GenderEditFields();
            $genderEditFields->setGender($user->getGender() ?? '');

            $genderEditForm = $this->createForm(GenderEditType::class, $genderEditFields);
            $genderEditForm->handleRequest($this->requestStack->getCurrentRequest());

            if($genderEditForm->isSubmitted() && $genderEditForm->isValid()) {
                $user->setGender($genderEditFields->getGender());

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->addFlash('information_saved', 'Information sauvegardée');

                return $this->redirectToRoute('account_identity');
            }

            return $this->render('user/account/personalInfos/identity/genderEdit.html.twig', [
                'gender_edit_form' => $genderEditForm->createView(),
            ]);
        }
    }
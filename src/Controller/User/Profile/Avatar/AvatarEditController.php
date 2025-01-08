<?php

    namespace App\Controller\User\Profile\Avatar;

    use App\Entity\User;
    use App\Form\Fields\Users\Profile\Avatar\AvatarManagerFields;
    use App\Form\Types\Users\Profile\Avatar\AvatarManagerType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class AvatarEditController extends AbstractController
    {
        public function  __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack
        ){}

        #[Route(path: '/user/profile/avatar/edit', name: 'user_profile_avatar_edit')]
        #[IsGranted('ROLE_USER')]
        public function avatarEdit(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                $this->createAccessDeniedException('Utilisateur invalide');
            }

            $userEntity = $user;

            $avatarFields = new AvatarManagerFields();

            $avatarType = $this->createForm(AvatarManagerType::class, $avatarFields);
            $avatarType->handleRequest($this->requestStack->getCurrentRequest());

            if($avatarType->isSubmitted() && $avatarType->isValid()) {
                $avatarData = $avatarFields->getProfilePic();

                if($avatarData) {
                    $avatarName = uniqid().'.'.$avatarData->guessExtension();
                    $avatarData->move($this->getParameter('user/profile/avatar'), $avatarName);
                }

                $userEntity->setProfilPic($avatarName);

                $this->entityManager->persist($userEntity);
                $this->entityManager->flush();

                $this->addFlash('profile_pic_saved', 'Photo de profile enregistré');

                return $this->redirectToRoute('user_profile');
            }

            return $this->render('user/profile/avatar/avatarEdit.html.twig', [
                'avatarForm' => $avatarType->createView(),
            ]);
        }
    }
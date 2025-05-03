<?php

    namespace App\Controller\User\Profile\Settings;

    use App\Entity\User;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class ConfirmNewEmailController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
        ){}


        #[Route(path: 'user/profil/settings/confirm-new-email/{token}', name: 'user_confirm_new_email')]
        public function confirmNewEmail(string $token): Response
        {
            $user = $this->entityManager->getRepository(User::class)->findOneBy([
                'emailChangeToken' => $token
            ]);

            if (!$user) {
                throw $this->createNotFoundException('Lien invalide ou expiré.');
            }

            $user->setEmail($user->getPendingEmail());
            $user->setPendingEmail(null);
            $user->setEmailChangeToken(null);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('changing_email_successfully', 'votre nouvelle adresse email a été confirmé');

            return $this->redirectToRoute('user_logout');
        }
    }
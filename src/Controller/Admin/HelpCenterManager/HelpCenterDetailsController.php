<?php

    namespace App\Controller\Admin\HelpCenterManager;

    use App\Entity\Admin;
    use App\Entity\HelpCenter;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class HelpCenterDetailsController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
        ){}



        #[Route(path: '/admin/help-center/details/{email}', name: 'admin_hel_center_details')]
        #[IsGranted('ROLE_ADMIN')]
        public function helpCenterDetails(string $email): Response
        {
            $admin = $this->getUser();

            if (!$admin instanceof Admin) {
                throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette page');
            }

            $requestForAid = $this->entityManager->getRepository(HelpCenter::class)->findBy(
                ['email' => $email]
            );

            return $this->render('admin/helpCenterManager/helpCenterDetails.html.twig', [
                'email' => $email,
                'request_for_aid' => $requestForAid
            ]);
        }
    }
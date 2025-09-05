<?php

    namespace App\Controller\Admin\HelpCenterManager;

    use App\Entity\Admin;
    use App\Entity\HelpCenter;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class HelpCenterListController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
        ){}



        #[Route(path: '/admin/help-center/list', name: 'admin_help_center_list')]
        #[IsGranted('ROLE_ADMIN')]
        public function helpCenterList(): Response
        {
            $admin = $this->getUser();

            if (!$admin instanceof Admin)  {
                throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette page');
            }

            // Get all emails of users who have submitted a request
            $allEmails = $this->entityManager->getRepository(HelpCenter::class)
                ->createQueryBuilder('h')
                ->select('DISTINCT h.id, h.email')
                ->orderBy('h.id', 'ASC')       // Tri du plus ancien au plus recent
                ->getQuery()
                ->getResult()
            ;

            return $this->render('admin/helpCenterManager/helpCenterList.html.twig', [
                'all_emails' => $allEmails,
            ]);
        }
    }
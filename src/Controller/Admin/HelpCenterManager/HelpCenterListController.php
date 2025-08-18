<?php

    namespace App\Controller\Admin\HelpCenterManager;

    use App\Entity\Admin;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class HelpCenterListController extends AbstractController
    {
        #[Route(path: '/admin/help-center/list', name: 'admin_help_center_list')]
        #[IsGranted('ROLE_ADMIN')]
        public function helpCenterList(): Response
        {
            $admin = $this->getUser();

            if (!$admin instanceof Admin)  {
                throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette page');
            }


            return $this->render('admin/helpCenterManager/helpCenterList.html.twig');
        }
    }
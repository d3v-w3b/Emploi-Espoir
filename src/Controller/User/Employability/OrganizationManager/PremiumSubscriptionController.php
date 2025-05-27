<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class PremiumSubscriptionController extends AbstractController
    {
        #[Route(path: '/organization/subscription/premium-subscription', name: 'organization_premium_subscription')]
        #[isGranted('ROLE_ENT')]
        public function premiumSubscription(): Response
        {
            return $this->render('user/employability/organizationManager/premiumSubscription.html.twig');
        }
    }
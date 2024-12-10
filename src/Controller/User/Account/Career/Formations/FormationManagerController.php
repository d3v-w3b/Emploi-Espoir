<?php

    namespace App\Controller\User\Account\Career\Formations;

    use App\Form\Fields\Users\Account\Career\Formation\FormationManagerFields;
    use App\Form\Types\Users\Account\Career\Formation\FormationManagerTypes;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class FormationManagerController extends AbstractController
    {
        #[Route(path: '/account/formations/formation', name: 'account_formation_add')]
        #[IsGranted('ROLE_USER')]
        public function formationManager(): Response
        {
            $formationManagerFields = new FormationManagerFields();

            $formationManagerType = $this->createForm(FormationManagerTypes::class, $formationManagerFields);

            return $this->render('user/account/career/formations/formationManager.html.twig', [
                'formation_form' => $formationManagerType->createView(),
            ]);
        }
    }
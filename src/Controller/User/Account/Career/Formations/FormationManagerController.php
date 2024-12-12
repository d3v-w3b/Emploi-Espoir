<?php

    namespace App\Controller\User\Account\Career\Formations;

    use App\Entity\Formation;
    use App\Form\Fields\Users\Account\Career\Formation\FormationManagerFields;
    use App\Form\Types\Users\Account\Career\Formation\FormationManagerTypes;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class FormationManagerController extends AbstractController
    {
        private RequestStack $requestStack;
        private EntityManagerInterface $entityManager;


        public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
        {
            $this->entityManager = $entityManager;
            $this->requestStack = $requestStack;
        }


        #[Route(path: '/account/formations/formation', name: 'account_formation_add')]
        #[IsGranted('ROLE_USER')]
        public function formationManager(): Response
        {
            // get current user
            $user = $this->getUser();

            $formationManagerFields = new FormationManagerFields();
            $formationEntity = $user->getFormation() ?? new Formation();

            $formationManagerType = $this->createForm(FormationManagerTypes::class, $formationManagerFields);

            $formationManagerType->handleRequest($this->requestStack->getCurrentRequest());

            if($formationManagerType->isSubmitted() && $formationManagerType->isValid()) {

                $formationEntity->setDiplomaName($formationManagerFields->getDiplomaName());
                $formationEntity->setDiplomaSpeciality($formationManagerFields->getDiplomaSpeciality());
                $formationEntity->setDiplomaLevel($formationManagerFields->getDiplomaLevel());
                $formationEntity->setUniversityName($formationManagerFields->getUniversityName());
                $formationEntity->setDiplomaTown($formationManagerFields->getDiplomaTown());
                $formationEntity->setDiplomaMonth($formationManagerFields->getDiplomaMonth());
                $formationEntity->setDiplomaYear($formationManagerFields->getDiplomaYear());

                // diploma files management
                $diplomaFiles = $formationManagerFields->getDiploma();
                $diploma = $formationEntity->getDiploma() ?? [];

                foreach ($diplomaFiles as $diplomaFile) {
                    // manage each file
                    $destination = $this->getParameter('user/career/formation/diploma');
                    $fileName = uniqid(). '.' .$diplomaFile->guessExtension();
                    $diplomaFile->move($destination, $fileName);

                    $diploma[] = $destination. '/' .$fileName;
                }

                $formationEntity->setDiploma($diploma);

                // connect entities
                $user->setFormation($formationEntity);
                $formationEntity->setUser($user);

                $this->entityManager->persist($formationEntity);
                $this->entityManager->flush();

                $this->addFlash('formation_information_saved', 'Information sauvegardée');

                return $this->redirectToRoute('account_formations');
            }

            return $this->render('user/account/career/formations/formationManager.html.twig', [
                'formation_form' => $formationManagerType->createView(),
            ]);
        }
    }
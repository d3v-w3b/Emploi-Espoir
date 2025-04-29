<?php

    namespace App\Controller\User\Account\Career\Formations;

    use App\Entity\Formation;
    use App\Entity\User;
    use App\Form\Fields\Users\Account\Career\Formation\FormationEditFields;
    use App\Form\Types\Users\Account\Career\Formation\FormationEditType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class FormationEditController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack,
        ){}



        #[Route(path: '/account/formations/formation/edit/{id}', name: 'account_formation_edit')]
        #[IsGranted('ROLE_USER')]
        public function formationEdit(int $id): Response
        {
            $user = $this->getUser();

            if (!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $currentFormation = $this->entityManager->getRepository(Formation::class)->find($id);

            //dd($currentFormation->getDiploma());

            $formationEditFields = new FormationEditFields();
            $formationEditFields->setDiplomaLevel($currentFormation->getDiplomaLevel());
            $formationEditFields->setDiplomaName($currentFormation->getDiplomaName());
            $formationEditFields->setDiplomaSpeciality($currentFormation->getDiplomaSpeciality());
            $formationEditFields->setUniversityName($currentFormation->getUniversityName());
            $formationEditFields->setDiplomaTown($currentFormation->getDiplomaTown());
            $formationEditFields->setDiplomaMonth($currentFormation->getDiplomaMonth());
            $formationEditFields->setDiplomaYear($currentFormation->getDiplomaYear());
            $formationEditFields->setDiploma($currentFormation->getDiploma());

            $formationForm = $this->createForm(FormationEditType::class, $formationEditFields);
            $formationForm->handleRequest($this->requestStack->getCurrentRequest());

            if ($formationForm->isSubmitted() && $formationForm->isValid()) {
                //connect entities
                $currentFormation->addUser($user);
                $user->addFormation($currentFormation);

                $currentFormation->setDiplomaLevel($formationEditFields->getDiplomaLevel());
                $currentFormation->setDiplomaName($formationEditFields->getDiplomaName());
                $currentFormation->setDiplomaSpeciality($formationEditFields->getDiplomaSpeciality());
                $currentFormation->setUniversityName($formationEditFields->getUniversityName());
                $currentFormation->setDiplomaTown($formationEditFields->getDiplomaTown());
                $currentFormation->setDiplomaMonth($formationEditFields->getDiplomaMonth());
                $currentFormation->setDiplomaYear($formationEditFields->getDiplomaYear());


                $removedFiles = json_decode($formationForm->get('removed_files')->getData(), true) ?? [];

                $updatedDiplomas = [];

                foreach ($currentFormation->getDiploma() as $file) {
                    if (!in_array($file, $removedFiles, true)) {
                        $updatedDiplomas[] = $file;
                    }
                }

                // Ajouter les nouveaux fichiers s'il y en a
                if ($formationEditFields->getDiploma()) {
                    foreach ($formationEditFields->getDiploma() as $newFile) {
                        if ($newFile instanceof UploadedFile) {
                            $newFileName = $newFile->getClientOriginalName();
                            $newFileDestination = $this->getParameter('user/career/formation/diploma');
                            $newFile->move($newFileDestination, $newFileName);

                            $updatedDiplomas[] = $newFileDestination. '/' .$newFileName;
                        }
                    }
                }

                $currentFormation->setDiploma($updatedDiplomas);

                $this->entityManager->persist($currentFormation);
                $this->entityManager->flush();

                $this->addFlash('formation_information_saved', 'Information sauvegardée');

                return $this->redirectToRoute('account_formations');
            }

            return $this->render('user/account/career/formations/formationEdit.html.twig', [
                'formation' => $currentFormation,
                'formation_edit_form' => $formationForm->createView()
            ]);
        }
    }
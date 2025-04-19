<?php

    namespace App\Controller\User\Account\Alternation;

    use App\Entity\JobAndAlternation;
    use App\Entity\User;
    use App\Form\Fields\Users\Account\Alternation\AlternationSearchZoneFields;
    use App\Form\Types\Users\Account\Alternation\AlternationSearchZoneType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class AlternationSearchZoneController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack
        ){}


        #[Route(path: '/account/alternation-preferences/alternation-zone', name: 'account_alternation_preferences_alternation_zone')]
        #[IsGranted('ROLE_USER')]
        public function alternationSearchZone(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $alternationZoneFields = new AlternationSearchZoneFields();

            if($user->getJobAndAlternation()) {
                $alternationZoneFields->setAlternationZone($user->getJobAndAlternation()->getAlternationZone());
            }


            $alternationZoneForm = $this->createForm(AlternationSearchZoneType::class, $alternationZoneFields);
            $alternationZoneForm->handleRequest($this->requestStack->getCurrentRequest());

            $alternationEntity = $user->getJobAndAlternation() ?? new JobAndAlternation();

            if($alternationZoneForm->isSubmitted() && $alternationZoneForm->isValid()) {
                // connect entities
                $alternationEntity->setUser($user);
                $user->setJobAndAlternation($alternationEntity);

                $user->getJobAndAlternation()->setAlternationZone($alternationZoneFields->getAlternationZone());

                $this->entityManager->persist($alternationEntity);
                $this->entityManager->flush();

                $this->addFlash('preferences_saved', 'Information sauvegardée');

                return $this->redirectToRoute('account_alternation_preferences');
            }

            return $this->render('user/account/alternation/alternationSearchZone.html.twig', [
                'alternation_zone_form' => $alternationZoneForm->createView(),
            ]);
        }
    }
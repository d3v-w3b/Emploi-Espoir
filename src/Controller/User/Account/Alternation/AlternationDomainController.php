<?php

    namespace App\Controller\User\Account\Alternation;

    use App\Entity\JobAndAlternation;
    use App\Entity\User;
    use App\Form\Fields\Users\Account\Alternation\AlternationDomainFields;
    use App\Form\Types\Users\Account\Alternation\AlternationDomainType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class AlternationDomainController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack
        ){}


        #[Route(path: '/account/alternation-preferences/alternation-domain', name: 'account_alternation_preference_alternation_domain')]
        #[IsGranted('ROLE_USER')]
        public function alternationDomain(): Response
        {
            $user = $this->getUser();

            if(!$user instanceof User) {
                throw $this->createAccessDeniedException('Utilisateur invalide');
            }

            $alternationDomainFields = new AlternationDomainFields();

            if($user->getJobAndAlternation()) {
                $alternationDomainFields->setAlternationDomain($user->getJobAndAlternation()->getAlternationField());
            }
            
            $alternationDomainForm = $this->createForm(AlternationDomainType::class, $alternationDomainFields);
            $alternationDomainForm->handleRequest($this->requestStack->getCurrentRequest());

            $alternationEntity = $user->getJobAndAlternation() ?? new JobAndAlternation();

            if($alternationDomainForm->isSubmitted() && $alternationDomainForm->isValid()) {
                // Connect entities
                $alternationEntity->setUser($user);
                $user->setJobAndAlternation($alternationEntity);

                $user->getJobAndAlternation()->setAlternationField($alternationDomainFields->getAlternationDomain());

                $this->entityManager->persist($alternationEntity);
                $this->entityManager->flush();

                $this->addFlash('preferences_saved', 'Information sauvegardée');

                return $this->redirectToRoute('account_alternation_preferences');
            }


            return $this->render('user/account/alternation/alternationDomain.html.twig', [
                'alternation_domain_form' => $alternationDomainForm->createView(),
            ]);
        }
    }
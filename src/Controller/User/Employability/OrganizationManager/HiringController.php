<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Entity\Applicant;
    use App\Entity\Hiring;
    use App\Entity\JobOffers;
    use App\Entity\User;
    use App\Form\Fields\Users\Employability\OrganizationManager\HiringFields;
    use App\Form\Types\Users\Employability\OrganizationManager\HiringType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bridge\Twig\Mime\TemplatedEmail;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class HiringController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack,
            private readonly MailerInterface $mailer
        ){}



        #[Route(path: '/organization/candidate/hiring/{id}', name: 'organization_candidate_hiring')]
        #[IsGranted('ROLE_ENT')]
        public function hiring(int $id): Response
        {
            // This user represents the owner of the current org
            $user = $this->getUser();

            if (!$user instanceof User) {
                throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette page');
            }

            $currentCandidat = $this->entityManager->getRepository(Applicant::class)->find($id);

            // Forbidden organization to hiring the same candidate
            $candidateAlreadyContacted = $this->entityManager->getRepository(Hiring::class)->findOneBy([
                'applicant' => $currentCandidat
            ]);

            if ($candidateAlreadyContacted) {
                $this->addFlash('candidate_already_contacted', 'Vous avez déjà contacté ce candidat');

                return $this->redirectToRoute('organization_offer_applicant_details', [
                    'applicantId' => $id
                ]);
            }

            // Get the job title we are recruiting the candidate for from the current organization
            $currentOrg = $this->entityManager->getRepository(JobOffers::class)->findOneBy([
                'organization' => $user->getOrganization()
            ]);

            $hiringEntity = new Hiring();
            $hiringFields = new HiringFields();

            $organizationResponse = "Bonjour {$currentCandidat->getFirstName()}, \nAprès avoir découvert votre profil sur Emploi Espoir, nous sommes vivement intéressés par votre parcours. Nous aimerions convenir d'un premier rendez-vous pour discuter d'une opportunité en {$currentOrg->getTypeOfContract()} chez {$currentOrg->getOrganization()->getOrganizationName()}. \nPourriez-vous nous indiquer vos disponibilités pour un premier échange ?\nDans l’attente de votre retour,\nBien à vous,";

            $hiringFields->setOrgOwnerFirstName($user->getFirstName());
            $hiringFields->setOrgOwnerLastName($user->getLastName());
            $hiringFields->setOrgOwnerEmail($user->getEmail());
            $hiringFields->setOrgOwnerPhone($user->getPhone());
            $hiringFields->setOrganizationResponse($organizationResponse);

            $hiringForm = $this->createForm(HiringType::class, $hiringFields, [
                'with_offer' => false,
            ]);
            $hiringForm->handleRequest($this->requestStack->getCurrentRequest());

            if ($hiringForm->isSubmitted() && $hiringForm->isValid()) {
                // Connect entities
                $hiringEntity->setOrganization($user->getOrganization());
                $hiringEntity->setApplicant($currentCandidat);

                $hiringEntity->setOrganizationResponse($hiringFields->getOrganizationResponse());
                $hiringEntity->setOrgOwnerEmail($hiringFields->getOrgOwnerEmail());
                $hiringEntity->setOrgOwnerFirstName($hiringFields->getOrgOwnerFirstName());
                $hiringEntity->setOrgOwnerLastName($hiringFields->getOrgOwnerLastName());
                $hiringEntity->setOrgOwnerPhone($hiringFields->getOrgOwnerPhone());

                $this->entityManager->persist($hiringEntity);
                $this->entityManager->flush();

                $candidatContactedSaved = $this->entityManager->getRepository(Hiring::class)->findOneBy([
                    'applicant' => $currentCandidat
                ]);

                //Send email if flush has been done
                if ($candidatContactedSaved) {

                    try {
                        $email = (new TemplatedEmail())
                            ->from('EmploiEspoir-Admin@admin.fr')
                            ->to($currentCandidat->getEmail())
                            ->subject('Entretien d\'embauche pour le poste de '.$currentCandidat->getOffer().' - '.$user->getOrganization()->getOrganizationName())
                            ->htmlTemplate('user/employability/organizationManager/hiringEmail.html.twig')
                            ->context([
                                'organizationResponse' => $hiringEntity->getOrganizationResponse(),
                                'orgOwnerFirstName' => $hiringEntity->getOrgOwnerFirstName(),
                                'orgOwnerLastName' => $hiringEntity->getOrgOwnerLastName(),
                                'orgOwnerEmail' => $hiringEntity->getOrgOwnerEmail(),
                                'orgOwnerPhone' => $hiringEntity->getOrgOwnerPhone(),
                            ])
                        ;

                        $this->mailer->send($email);
                    }
                    catch (TransportExceptionInterface $e)  {
                        $this->addFlash('error_sending', $e->getMessage());
                    }

                    return $this->redirectToRoute('organization_candidate_infos', [
                        'id' => $id
                    ]);
                }
            }

            return $this->render('user/employability/organizationManager/hiring.html.twig', [
                'current_candidate' => $currentCandidat,
                'hiring_form' => $hiringForm->createView(),
            ]);
        }
    }
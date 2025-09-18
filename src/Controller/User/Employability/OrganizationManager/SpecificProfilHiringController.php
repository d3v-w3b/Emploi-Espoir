<?php

    namespace App\Controller\User\Employability\OrganizationManager;

    use App\Entity\Applicant;
    use App\Entity\Hiring;
    use App\Entity\JobOffers;
    use App\Entity\User;
    use App\Enum\User\Employability\OrganizationManager\ApplicantSource;
    use App\Form\Fields\Users\Employability\OrganizationManager\HiringFields;
    use App\Form\Types\Users\Employability\OrganizationManager\HiringType;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bridge\Twig\Mime\TemplatedEmail;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

    class SpecificProfilHiringController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager,
            private readonly RequestStack $requestStack,
            private readonly MailerInterface $mailer
        ){}



        #[Route(path: '/organization/specific-profil/{id}/hiring', name: 'organization_specific_profil_hiring')]
        #[IsGranted('ROLE_ENT')]
        public function specificProfilHiring(int $id): Response
        {
            $orgOwner = $this->getUser();

            if (!$orgOwner instanceof User) {
                throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette page');
            }

            $specificProfil = $this->entityManager->getRepository(User::class)->find($id);

            // Forbidden organization to hiring the same candidat
            $specificProfilApplicant = $this->entityManager->getRepository(Applicant::class)->findOneBy([
                'user' => $specificProfil
            ]);

            if ($specificProfilApplicant) {

                $specificProfilAlreadyContacted = $this->entityManager->getRepository(Hiring::class)->findOneBy([
                    'applicant' => $specificProfilApplicant,
                ]);

                if ($specificProfilAlreadyContacted) {
                    $this->addFlash('specific_profil_already_contacted', 'Vous avez déjà contacter ce candidat');

                    return $this->redirectToRoute('organization_specific_profil_details', [
                        'id' => $id
                    ]);
                }
            }

            // Get the job title we are recruiting the candidate for from the current organization
            $currentOrg = $this->entityManager->getRepository(JobOffers::class)->findOneBy([
                'organization' => $orgOwner->getOrganization()
            ]);

            $applicantEntity = new Applicant();
            $hiringEntity = new Hiring();

            $specificProfilHiringFields = new HiringFields();

            $organizationResponse = "Bonjour {$specificProfil->getFirstName()}, \nAprès avoir découvert votre profil sur Emploi Espoir, nous sommes vivement intéressés par votre parcours. Nous aimerions convenir d'un premier rendez-vous pour discuter d'une opportunité en {$currentOrg->getTypeOfContract()} chez {$currentOrg->getOrganization()->getOrganizationName()}. \nPourriez-vous nous indiquer vos disponibilités pour un premier échange ?\nDans l’attente de votre retour,\nBien à vous,";

            $specificProfilHiringFields->setOrgOwnerPhone($orgOwner->getPhone());
            $specificProfilHiringFields->setOrgOwnerLastName($orgOwner->getLastName());
            $specificProfilHiringFields->setOrgOwnerEmail($orgOwner->getEmail());
            $specificProfilHiringFields->setOrgOwnerFirstName($orgOwner->getFirstName());
            $specificProfilHiringFields->setOrganizationResponse($organizationResponse);

            $specificProfilHiringForm = $this->createForm(HiringType::class, $specificProfilHiringFields, [
                'with_offer' => true,
            ]);
            $specificProfilHiringForm->handleRequest($this->requestStack->getCurrentRequest());

            if ($specificProfilHiringForm->isSubmitted() && $specificProfilHiringForm->isValid()) {
                // Add the specific profil into Applicant entity
                $applicantEntity->setSource(ApplicantSource::HEADHUNTED);
                $applicantEntity->setEmail($specificProfil->getEmail());
                $applicantEntity->setLastName($specificProfil->getLastName());
                $applicantEntity->setFirstName($specificProfil->getFirstName());
                $applicantEntity->setPhone($specificProfil->getPhone() ?? '');
                $applicantEntity->setOffer($specificProfilHiringFields->getOffer());
                $applicantEntity->setDocsToProvide(array());

                // Connect entities about Applicant entity
                $applicantEntity->setUser($specificProfil);
                //$applicantEntity->addJobOffer($currentOrg)
                $specificProfil->addApplicant($applicantEntity);

                // Add specific profil into Hiring entity
                $hiringEntity->setOrganizationResponse($specificProfilHiringFields->getOrganizationResponse());
                $hiringEntity->setOrgOwnerFirstName($specificProfilHiringFields->getOrgOwnerFirstName());
                $hiringEntity->setOrgOwnerLastName($specificProfilHiringFields->getOrgOwnerLastName());
                $hiringEntity->setOrgOwnerEmail($specificProfilHiringFields->getOrgOwnerEmail());
                $hiringEntity->setOrgOwnerPhone($specificProfilHiringFields->getOrgOwnerPhone());

                // Connect entities about Hiring entity
                $hiringEntity->setOrganization($orgOwner->getOrganization());
                $hiringEntity->setApplicant($applicantEntity);

                $this->entityManager->persist($hiringEntity);
                $this->entityManager->persist($applicantEntity);

                $this->entityManager->flush();

                // Check if the flush has been done
                $specificProfilContacted = $this->entityManager->getRepository(Applicant::class)->findOneBy([
                    'user' => $specificProfil
                ]);

                // Send email if the flush has been done
                if ($specificProfilContacted) {
                    try {
                        $email = (new TemplatedEmail())
                            ->from('EmploiEspoir-Admin@admin.fr')
                            ->to($hiringEntity->getApplicant()->getEmail())
                            ->subject('Entretien d\'embauche pour le poste de '.$specificProfilContacted->getOffer().' - '.$orgOwner->getOrganization()->getOrganizationName())
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

                    return $this->redirectToRoute('specific_profil_infos', [
                        'id' => $id
                    ]);
                }
            }

            return $this->render('user/employability/organizationManager/specificProfilHiring.html.twig', [
                'specific_profil' => $specificProfil,
                'specific_profil_hiring_form' => $specificProfilHiringForm->createView(),
            ]);
        }
    }
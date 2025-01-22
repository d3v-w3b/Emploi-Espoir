<?php

    namespace App\Controller\Public;

    use App\Entity\JobOffers;
    use App\Entity\Organization;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class HomeController extends AbstractController
    {
        public function __construct(
            private readonly EntityManagerInterface $entityManager
        ){}


        #[Route(path: '/', name: 'home')]
        public function home(): Response
        {
            // default value if user does not exist
            $organizationEntity = null;

            // if an organization is associated to a user, put
            // organization name is the view
            $user = $this->getUser();
            if($user && method_exists($user, 'getOrganization')) {
                $organizationEntity = $user->getOrganization();
            }

            $jobOffers = $this->entityManager->getRepository(JobOffers::class)->findBy([]);


            return $this->render('public/home.html.twig', [
                'organization' => $organizationEntity,
                'job_offers' => $jobOffers
            ]);
        }
    }
<?php

    namespace App\DataFixtures;

    use App\Entity\Applicant;
    use App\Entity\JobOffers;
    use App\Entity\User;
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
    use Doctrine\Persistence\ObjectManager;

    class ApplicantFixtures extends Fixture implements FixtureGroupInterface
    {
        public function load(ObjectManager $manager): void
        {
            // get all job offers
            $jobOffers = $manager->getRepository(JobOffers::class)->findAll();

            // get all users
            $users = $manager->getRepository(User::class)->findAll();


            //$user = $user->jobOffer
            foreach ($users as $user) {
                foreach ($jobOffers as $jobOffer) {
                    $applicant = new Applicant();

                    //connect entities
                    $applicant->setUser($user);
                    $user->addApplicant($applicant);
                    $applicant->addJobOffer($jobOffer);

                    $applicant->setOffer($jobOffer->getJobTitle());
                    $applicant->setLastName($user->getLastName()); // Utiliser les infos du User qui postule
                    $applicant->setPhone($user->getPhone() ?? '0102030405');
                    $applicant->setEmail($user->getEmail());
                    $applicant->setFirstName($user->getFirstName());
                    $applicant->setDocsToProvide(['value-1', 'value-2']);

                    $manager->persist($applicant);
                }
            }

            $manager->flush();
        }



        public static function getGroups(): array
        {
            return ['applicant_fixtures'];
        }
    }
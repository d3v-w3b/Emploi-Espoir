<?php

    namespace App\DataFixtures;

    use App\Entity\JobOffers;
    use App\Entity\Organization;
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
    use Doctrine\Persistence\ObjectManager;

    class JobOffersFixtures extends Fixture implements FixtureGroupInterface
    {
        public function load(ObjectManager $manager): void
        {
            $organizations = $manager->getRepository(Organization::class)->findAll();

            // Array to allows to choice by random way between
            // alternance and premier emploi
            $typeOfContract = ['Alternance', 'Premier emploi'];

            foreach ($organizations as $organization) {
                for ($i = 1; $i <= 25; $i++) {

                    $jobOffer = new JobOffers();
                    $jobOffer->setOrganization($organization);
                    $jobOffer->setJobTitle('Développeur');
                    $jobOffer->setTown('Abidjan');
                    $jobOffer->setJobPreferences('Pas de télétravail');

                    // choice by random way between alternance and premier emploi
                    $randomTypeOfContract = $typeOfContract[array_rand($typeOfContract)];
                    $jobOffer->setTypeOfContract($randomTypeOfContract);

                    $manager->persist($jobOffer);
                }
            }

            $manager->flush();
        }


        public static function getGroups(): array
        {
            return ['job_offers_fixtures'];
        }
    }
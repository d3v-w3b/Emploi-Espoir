<?php

    namespace App\DataFixtures;

    use App\Entity\JobAndAlternation;
    use App\Entity\User;
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Persistence\ObjectManager;
    use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

    class JobAndAlternationFixtures extends Fixture implements FixtureGroupInterface
    {
        public function load(ObjectManager $manager): void
        {
            //get all entities from user table
            $users = $manager->getRepository(User::class)->findAll();

            foreach ($users as $user) {
                $jobAndAlternationEntity = new JobAndAlternation();

                $jobAndAlternationEntity->setUser($user);
                $jobAndAlternationEntity->setAlternationZone('Abidjan-1');
                $jobAndAlternationEntity->setAlternationPreference(['Pas de télétravail', 'télétravail partiel']);
                $jobAndAlternationEntity->setAlternationField('Communication');
                $jobAndAlternationEntity->setEmploymentArea( 'Dubai-1');
                $jobAndAlternationEntity->setEmploymentPreference(['Télétravail partiel', 'Télétravail uniquement']);

                $manager->persist($jobAndAlternationEntity);
            }

            $manager->flush();
        }


        public static function getGroups(): array
        {
            return ['job_and_alternation_fixture'];
        }
    }

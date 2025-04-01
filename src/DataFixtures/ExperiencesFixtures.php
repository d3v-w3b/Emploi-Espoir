<?php

    namespace App\DataFixtures;

    use App\Entity\Experiences;
    use App\Entity\User;
    use Doctrine\Persistence\ObjectManager;
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

    class ExperiencesFixtures extends Fixture implements FixtureGroupInterface
    {
        public function load(ObjectManager $manager): void
        {
            $allUsers = $manager->getRepository(User::class)->findAll();

            foreach ($allUsers as $user) {
                for ($i=1; $i<=4; $i++) {

                    $experience = new Experiences();
                    $experience->setUser($user);
                    $experience->setJobTitle('Développeur-'.$i);
                    $experience->setJobField(['informatique_tic']);
                    $experience->setTown('Abidjan'.$i.'.0');
                    $experience->setEnterpriseName('ORANGE-CI');
                    $experience->setJobDescription('SuperJob de Dev');

                    $manager->persist($experience);
                }
            }

            $manager->flush();
        }



        public static function getGroups(): array
        {
            return ['experiences_fixtures'];
        }
    }
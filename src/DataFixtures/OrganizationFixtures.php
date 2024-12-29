<?php

    namespace App\DataFixtures;

    use App\Entity\Organization;
    use App\Entity\User;
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
    use Doctrine\Persistence\ObjectManager;

    class OrganizationFixtures extends Fixture implements FixtureGroupInterface
    {
        public function load(ObjectManager $manager): void
        {
            $users = $manager->getRepository(User::class)->findAll();

            foreach ($users as $user) {
                $organization = new Organization();

                $organization->setUser($user);
                $organization->setTown('Abidjan');
                $organization->setOrganizationName('Emploi Espoir 2.0');
                $organization->setOrganizationRegistrationNumber('EM-346429943-F');
                //$organization->setNumberOfCollaborator();
                //$organization->setNeed();
                //$organization->setMessage();

                $manager->persist($organization);
            }

            $manager->flush();
        }


        public static function getGroups(): array
        {
            return ['organization_fixtures'];
        }
    }
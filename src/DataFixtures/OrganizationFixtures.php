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
                $organization->setOrganizationName($user->getFirstName().' Emploi Espoir 2.0');
                $organization->setOrganizationRegistrationNumber('EM-' . rand(100000000, 999999999) . '-F');

                $user->setRoles(array_unique([...$user->getRoles(), 'ROLE_ENT']));
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
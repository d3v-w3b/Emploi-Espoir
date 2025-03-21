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

            // Array to allows to choice sectors of activities for each organization
            $sectorOfActivity = [
                'Agriculture',
                'Commerce',
                'Informatique',
                'Communication',
                'Banque',
                'Santé',
                'Éducation',
                'BTP',
                'Transport',
                'Énergie',
                'Tourisme',
                'Textile',
                'Artisanat',
                'Industrie',
                'Immobilier',
                'Arts',
                'Environnement',
                'Services',
            ];

            // Array to allows to choice organization's preferences
            $organizationPreferences = ['Alternance', 'Premier emploi'];

            foreach ($users as $user) {
                $organization = new Organization();

                $organization->setUser($user);
                $organization->setTown('Abidjan');
                $organization->setOrganizationName($user->getFirstName().' Emploi Espoir 2.0');
                $organization->setOrganizationRegistrationNumber('EM-' . rand(100000000, 999999999) . '-F');

                // Add ROLE_ENT to user during creation of his organization
                $user->setRoles(array_unique([...$user->getRoles(), 'ROLE_ENT']));

                // Choice by random way sector of activity for each entry
                $randomKeys = (array) array_rand($sectorOfActivity, rand(2, 3));
                $selectedSectors = array_map(fn($key) => $sectorOfActivity[$key], $randomKeys);
                $organization->setSectorOfActivity($selectedSectors);

                // Choice by random way organization's preferences for each entry
                $randomKeys = (array) array_rand($organizationPreferences, rand(1, 2));
                $selectedPreferences = array_map(fn($key) => $organizationPreferences[$key], $randomKeys);
                $organization->setOrganizationPreferences($selectedPreferences);

                $manager->persist($organization);
            }

            $manager->flush();
        }


        public static function getGroups(): array
        {
            return ['organization_fixtures'];
        }
    }
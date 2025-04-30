<?php

    namespace App\DataFixtures;

    use App\Entity\Formation;
    use App\Entity\User;
    use App\Enum\User\Account\Career\Formation\DiplomaSpeciality;
    use App\Enum\User\Account\Career\Formation\Months;
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
    use Doctrine\Persistence\ObjectManager;

    class FormationsFixtures extends Fixture implements FixtureGroupInterface
    {
        public function load(ObjectManager $manager): void
        {
            //get all users to form the User table
            $users = $manager->getRepository(User::class)->findAll();

            foreach ($users as $user) {
                for ($i = 1; $i <= 5; $i++) {

                    $formation = new Formation();
                    $formation->addUser($user);
                    $formation->setDiplomaLevel('Bac +3/4');
                    $formation->setDiplomaName('MASTER'.$i);
                    $formation->setDiplomaSpeciality(DiplomaSpeciality::COMPUTER_SCIENCE);
                    $formation->setUniversityName('Université de HARVARD');
                    $formation->setDiplomaTown('Abidjan_'.$i);
                    $formation->setDiplomaMonth(Months::April);
                    $formation->setDiplomaYear('200'.$i);

                    $manager->persist($formation);
                }
            }

            $manager->flush();
        }


        public static function getGroups(): array
        {
            return ['formation_fixtures'];
        }
    }
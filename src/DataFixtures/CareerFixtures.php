<?php

    namespace App\DataFixtures;

    use App\Entity\Career;
    use App\Entity\User;
    use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Persistence\ObjectManager;

    class CareerFixtures extends Fixture implements FixtureGroupInterface
    {
        public function load(ObjectManager $manager): void
        {
            //get all entities from user table
            $users = $manager->getRepository(User::class)->findAll();

            foreach ($users as $user) {
                $career = new Career();

                $career->setUser($user);
                $career->setAboutYou('À Paris, la position de Julien envers Mme de Rênal eût été bien vite simplifiée ; mais à Paris, l’amour est fils des romans. Le jeune précepteur et sa timide maîtresse auraient retrouvé dans trois ou quatre romans, et jusque dans les couplets du Gymnase, l’éclaircissement de leur position. Les romans leur auraient tracé le rôle à jouer, montré le modèle à imiter ; et ce modèle, tôt ou tard, et quoique sans nul plaisir, et peut-être en rechignant, la vanité eût forcé Julien à le suivre.');
                //$career->setCv()
                $career->setSkills(['PHP', 'HTML', 'Javascript', 'Symfony']);
                $career->setExternalLink('https://github.com/');
                $career->setJobTitle('Job title');
                $career->setJobField(['Dévéloppement', 'bank finance', 'santé']);
                $career->setCountry('Côte d\'Ivoire');
                $career->setEnterpriseName('ORANGE DIGITAL CENTER');
                $career->setStartDate(new \DateTimeImmutable('2023-06-01'));
                $career->setEndDate(new \DateTimeImmutable('2024-01-01'));
                $career->setJobDescription('Ceci est une description du mon super job de dévéloppeur Web');
                $career->setFrenchLevel('langue maternelle');
                $career->setEnglishLevel('bilingue');

                $manager->persist($career);
            }

            $manager->flush();
        }


        public static function getGroups(): array
        {
            return ['career_fixture'];
        }
    }
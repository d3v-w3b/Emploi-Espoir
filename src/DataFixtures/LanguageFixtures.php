<?php

    namespace App\DataFixtures;

    use App\Entity\Language;
    use App\Entity\User;
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
    use Doctrine\Persistence\ObjectManager;

    class LanguageFixtures extends Fixture implements FixtureGroupInterface
    {
        public function load(ObjectManager $manager): void
        {
            $languages = ['Anglais', 'Arabe', 'Français'];
            $languageLevel = ['Débutant', 'Intermédiaire', 'Avancé'];

             $allUsers = $manager->getRepository(User::class)->findAll();

             foreach ($allUsers as $user) {
                 for ($i=1; $i<=5; $i++) {

                     $language = new Language();
                     $language->setUser($user);

                     $randomLanguage = $languages[array_rand($languages)];
                     $randomLanguageLevel = $languageLevel[array_rand($languageLevel)];

                     $language->setLanguage($randomLanguage);
                     $language->setLanguageLevel($randomLanguageLevel);

                     $manager->persist($language);
                 }
             }

             $manager->flush();
        }



        public static function getGroups(): array
        {
            return ['language_fixtures'];
        }
    }
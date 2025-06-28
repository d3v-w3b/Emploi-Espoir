<?php

    namespace App\DataFixtures;

    use App\Entity\User;
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
    use Doctrine\Persistence\ObjectManager;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

    class UsersFixtures extends Fixture implements FixtureGroupInterface
    {
        public function __construct(
            private readonly UserPasswordHasherInterface $passwordHasher
        ){}



        public function load(ObjectManager $manager): void
        {
            for ($i=1; $i<=15; $i++) {
                $user = new User();

                $user->setEmail('ange'.$i.'@free.fr');
                $user->setFirstName('ange'.$i);
                $user->setLastName('ouattara-'.$i);
                $user->setDateOfBirth(new \DateTimeImmutable('2000-02-20'));
                $user->setRoles(['ROLE_USER']);
                $user->setPassword($this->passwordHasher->hashPassword($user, 'emmaemma'));

                $manager->persist($user);
            }

            $manager->flush();
        }


        public static function getGroups(): array
        {
            return ['user_fixtures'];
        }
    }
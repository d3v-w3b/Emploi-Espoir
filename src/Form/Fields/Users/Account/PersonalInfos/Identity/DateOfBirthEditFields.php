<?php

    namespace App\Form\Fields\Users\Account\PersonalInfos\Identity;

    use Symfony\Component\Validator\Constraints as Assert;

    class DateOfBirthEditFields
    {
        #[Assert\NotBlank]
        private ?\DateTimeImmutable $dateOfBirth = null;


        public function setDateOfBirth(?\DateTimeImmutable $dateOfBirth): void
        {
            $this->dateOfBirth = $dateOfBirth;
        }

        public function getDateOfBirth(): ?\DateTimeImmutable
        {
            return $this->dateOfBirth;
        }
    }
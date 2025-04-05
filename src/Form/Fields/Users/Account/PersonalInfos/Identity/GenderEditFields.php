<?php

    namespace App\Form\Fields\Users\Account\PersonalInfos\Identity;

    use Symfony\Component\Validator\Constraints as Assert;

    class GenderEditFields
    {
        private ?string $gender = null;


        public function setGender(?string $gender): void
        {
            $this->gender = $gender;
        }


        public function getGender(): ?string
        {
            return $this->gender;
        }
    }
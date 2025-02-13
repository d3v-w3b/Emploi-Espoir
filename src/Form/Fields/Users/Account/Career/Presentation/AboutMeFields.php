<?php

    namespace App\Form\Fields\Users\Account\Career\Presentation;

    use Symfony\Component\Validator\Constraints as Assert;

    class AboutMeFields
    {
        #[Assert\NotBlank]
        #[Assert\Length(
            max: 300
        )]
        private ?string $aboutMe = null;


        public function setAboutMe(?string $aboutMe): void
        {
            $this->aboutMe = $aboutMe;
        }

        public function getAboutMe(): ?string
        {
            return $this->aboutMe;
        }
    }
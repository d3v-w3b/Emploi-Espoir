<?php

    namespace App\Form\Fields\Users\Account\Career\Language;

    use Symfony\Component\Validator\Constraints as Assert;

    class LanguageLevelEditFields
    {
        #[Assert\NotBlank]
        private ?string $languageLevel = null;


        public function setLanguageLevel(?string $languageLevel): void
        {
            $this->languageLevel = $languageLevel;
        }

        public function getLanguageLevel(): ?string
        {
            return $this->languageLevel;
        }
    }
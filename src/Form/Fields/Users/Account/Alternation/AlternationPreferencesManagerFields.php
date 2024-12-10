<?php

    namespace App\Form\Fields\Users\Account\Alternation;

    use Symfony\Component\Validator\Constraints as Assert;

    class AlternationPreferencesManagerFields
    {
        private ?array $alternationPreferences = null;


        public function setAlternationPreferences(?array $alternationPreferences): void
        {
            $this->alternationPreferences = $alternationPreferences;
        }

        public function getAlternationPreferences(): ?array
        {
            return $this->alternationPreferences;
        }
    }
<?php

    namespace App\Form\Fields\Users\Account\Alternation;

    use Symfony\Component\Validator\Constraints as Assert;

    class AlternationSearchZoneFields
    {
        #[Assert\NotBlank]
        private ?string $alternationZone = null;


        public function setAlternationZone(?string $alternationZone): void
        {
            $this->alternationZone = $alternationZone;
        }

        public function getAlternationZone(): ?string
        {
            return $this->alternationZone;
        }
    }
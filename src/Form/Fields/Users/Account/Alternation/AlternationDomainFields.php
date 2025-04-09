<?php

    namespace App\Form\Fields\Users\Account\Alternation;

    use Symfony\Component\Validator\Constraints as Assert;

    class AlternationDomainFields
    {
        #[Assert\NotBlank]
        private ?string $alternationDomain = null;


        public function setAlternationDomain(?string $alternationDomain): void
        {
            $this->alternationDomain = $alternationDomain;
        }

        public function getAlternationDomain(): ?string
        {
            return $this->alternationDomain;
        }
    }
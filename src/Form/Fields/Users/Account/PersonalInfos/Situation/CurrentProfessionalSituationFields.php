<?php

    namespace App\Form\Fields\Users\Account\PersonalInfos\Situation;

    use Symfony\Component\Validator\Constraints as Assert;

    class CurrentProfessionalSituationFields
    {
        #[Assert\NotBlank]
        private ?string $currentProfessionalSituation = null;


        public function setCurrentProfessionalSituation(?string $currentProfessionalSituation): void
        {
            $this->currentProfessionalSituation = $currentProfessionalSituation;
        }

        public function getCurrentProfessionalSituation(): ?string
        {
            return $this->currentProfessionalSituation;
        }
    }
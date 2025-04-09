<?php

    namespace App\Form\Fields\Users\Account\PersonalInfos\Situation;

    use Symfony\Component\Validator\Constraints as Assert;

    class MainObjectivesFields
    {
        #[Assert\NotBlank]
        private ?array $mainObjectives = null;


        public function setMainObjectives(?array $mainObjectives): void
        {
            $this->mainObjectives = $mainObjectives;
        }

        public function getMainObjectives(): ?array
        {
            return $this->mainObjectives;
        }
    }
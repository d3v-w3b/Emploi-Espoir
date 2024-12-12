<?php

    namespace App\Form\Fields\Users\Account\Career\Skills;

    class SkillsManagerFields
    {
        private ?array $skills = null;


        public function setSkills(?array $skills): void
        {
            $this->skills = $skills;
        }

        public function getSkills(): ?array
        {
            return $this->skills;
        }
    }
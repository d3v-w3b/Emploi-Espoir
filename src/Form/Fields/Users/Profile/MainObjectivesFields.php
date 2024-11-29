<?php

    namespace App\Form\Fields\Users\Profile;

    class MainObjectivesFields
    {
        private ?array $alternance = null;
        private ?array $job = null;


        //setters
        public function setAlternance(?array $alternance): void
        {
            $this->alternance = $alternance;
        }

        public function setJob(?array $job): void
        {
            $this->job = $job;
        }


        //getters
        public function getAlternance(): ?array
        {
            return $this->alternance;
        }

        public function getJob(): ?array
        {
            return $this->job;
        }
    }
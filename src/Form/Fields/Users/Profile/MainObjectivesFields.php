<?php

    namespace App\Form\Fields\Users\Profile;

    class MainObjectivesFields
    {
        //private ?array $alternance = null;
        //private ?array $job = null;

        private ?array $mainObjectives = null;


        //setters
        public function setMainObjectives(?array $mainObjectives): void
        {
            $this->mainObjectives = $mainObjectives;
        }

        /**
         * @param array|null $alternance
         * @return void
         *
         * public function setAlternance(?array $alternance): void
         * {
         * $this->alternance = $alternance;
         * }
         */

        /**
         * @param array|null $job
         * @return void
         *
         * public function setJob(?array $job): void
         * {
         * $this->job = $job;
         * }
         */



        //getters
        public function getMainObjectives(): ?array
        {
            return $this->mainObjectives;
        }
        /**
         * @return array|null
         *
         * public function getAlternance(): ?array
         * {
         * return $this->alternance;
         * }
         */


        /**
         * @return array|null
         *
         * public function getJob(): ?array
         * {
         * return $this->job;
         * }
         */

    }
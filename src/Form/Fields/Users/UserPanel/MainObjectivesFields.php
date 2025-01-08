<?php

    namespace App\Form\Fields\Users\UserPanel;

    class MainObjectivesFields
    {
        private ?array $mainObjectives = null;


        //setters
        public function setMainObjectives(?array $mainObjectives): void
        {
            $this->mainObjectives = $mainObjectives;
        }



        //getters
        public function getMainObjectives(): ?array
        {
            return $this->mainObjectives;
        }

    }
<?php

    namespace App\Form\Fields\Users\UserPanel;

    class MainObjectivesFields
    {
        private ?array $mainObjectives = null;
        private ?array $fields = null;


        //setters
        public function setMainObjectives(?array $mainObjectives): void
        {
            $this->mainObjectives = $mainObjectives;
        }

        public function setFields(?array $fields): void
        {
            $this->fields = $fields;
        }



        //getters
        public function getMainObjectives(): ?array
        {
            return $this->mainObjectives;
        }
        
        public function getFields(): ?array
        {
            return $this->fields;
        }
    }
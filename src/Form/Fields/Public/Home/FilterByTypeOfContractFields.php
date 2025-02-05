<?php

    namespace App\Form\Fields\Public\Home;

    //use Symfony\Component\Validator\Constraints as Assert;

    class FilterByTypeOfContractFields
    {
        private ?string $typeOfContract = null;


        public function setTypeOfContract(?string $typeOfContract): void
        {
            $this->typeOfContract = $typeOfContract;
        }

        public function getTypeOfContract(): ?string
        {
            return $this->typeOfContract;
        }
    }
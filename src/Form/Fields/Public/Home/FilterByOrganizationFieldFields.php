<?php

    namespace App\Form\Fields\Public\Home;

    //use Symfony\Component\Validator\Constraints as Assert;

    class FilterByOrganizationFieldFields
    {
        private ?string $organizationField = null;


        public function setOrganizationField(?string $organizationField): void
        {
            $this->organizationField = $organizationField;
        }

        public function getOrganizationField(): ?string
        {
            return $this->organizationField;
        }
    }
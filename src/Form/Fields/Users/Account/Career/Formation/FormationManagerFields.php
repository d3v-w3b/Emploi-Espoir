<?php

    namespace App\Form\Fields\Users\Account\Career\Formation;

    use Symfony\Component\Validator\Constraints as Assert;

    class FormationManagerFields
    {
        #[Assert\NotBlank(message: 'Sélectionnez le niveau du diplôme')]
        private ?string $diplomaLevel = null;

        #[Assert\NotBlank(message: 'Complétez le nom du diplôme')]
        private ?string $diplomaName = null;

        #[Assert\NotBlank(message: 'Sélectionnez la ou les spécialités du diplôme')]
        private ?array $diplomaSpecialities = null;

        #[Assert\NotBlank(message: 'Sélectionnez le nom l\'organisme')]
        private ?string $universityName = null;

        #[Assert\NotBlank(message: 'Sélectionnez la ville d\'obtention du diplôme')]
        private ?string $diplomaTown = null;
        private ?string $diplomaMonth = null;

        #[Assert\NotBlank(message: 'Sélectionnez l\'année de la date d\'obtention du diplôme')]
        private ?string $diplomaYear = null;

        #[Assert\File(
            maxSize: '5M',
            mimeTypes: ['application/pdf', 'application/doc', 'application/docx', 'image/jpeg', 'image/jpg', 'image/png'],
            mimeTypesMessage: 'Les extensions valident sont : .pdf, .doc, .docx, .jpeg, .jpg, .png'
        )]
        #[Assert\Count(
            max: 5,
            maxMessage: 'Vous ne pouvez charger que 5 fichiers'
        )]
        private ?array $diploma = null;


        //setters
        public function setDiploma(?array $diploma): void
        {
            $this->diploma = $diploma;
        }

        public function setDiplomaLevel(?string $diplomaLevel): void
        {
            $this->diplomaLevel = $diplomaLevel;
        }

        public function setDiplomaMonth(?string $diplomaMonth): void
        {
            $this->diplomaMonth = $diplomaMonth;
        }

        public function setDiplomaName(?string $diplomaName): void
        {
            $this->diplomaName = $diplomaName;
        }

        public function setDiplomaSpecialities(?array $diplomaSpecialities): void
        {
            $this->diplomaSpecialities = $diplomaSpecialities;
        }

        public function setDiplomaTown(?string $diplomaTown): void
        {
            $this->diplomaTown = $diplomaTown;
        }

        public function setDiplomaYear(?string $diplomaYear): void
        {
            $this->diplomaYear = $diplomaYear;
        }

        public function setUniversityName(?string $universityName): void
        {
            $this->universityName = $universityName;
        }


        //getters
        public function getDiploma(): ?array
        {
            return $this->diploma;
        }

        public function getDiplomaLevel(): ?string
        {
            return $this->diplomaLevel;
        }

        public function getDiplomaMonth(): ?string
        {
            return $this->diplomaMonth;
        }

        public function getDiplomaName(): ?string
        {
            return $this->diplomaName;
        }

        public function getDiplomaSpecialities(): ?array
        {
            return $this->diplomaSpecialities;
        }

        public function getDiplomaTown(): ?string
        {
            return $this->diplomaTown;
        }

        public function getDiplomaYear(): ?string
        {
            return $this->diplomaYear;
        }

        public function getUniversityName(): ?string
        {
            return $this->universityName;
        }
    }
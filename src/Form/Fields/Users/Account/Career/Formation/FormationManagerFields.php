<?php

    namespace App\Form\Fields\Users\Account\Career\Formation;

    use App\Enum\User\Account\Career\Formation\DiplomaSpeciality;
    use App\Enum\User\Account\Career\Formation\Months;
    use Symfony\Component\Validator\Constraints as Assert;

    class FormationManagerFields
    {
        private ?string $diplomaLevel = null;
        private ?string $diplomaName = null;
        private ?DiplomaSpeciality $diplomaSpeciality = null;
        private ?string $universityName = null;
        private ?string $diplomaTown = null;
        private ?Months $diplomaMonth = null;
        private ?string $diplomaYear = null;
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

        public function setDiplomaMonth(?Months $diplomaMonth): void
        {
            $this->diplomaMonth = $diplomaMonth;
        }

        public function setDiplomaName(?string $diplomaName): void
        {
            $this->diplomaName = $diplomaName;
        }

        public function setDiplomaSpeciality(?DiplomaSpeciality $diplomaSpeciality): void
        {
            $this->diplomaSpeciality = $diplomaSpeciality;
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

        public function getDiplomaMonth(): ?Months
        {
            return $this->diplomaMonth;
        }

        public function getDiplomaName(): ?string
        {
            return $this->diplomaName;
        }

        public function getDiplomaSpeciality(): ?DiplomaSpeciality
        {
            return $this->diplomaSpeciality;
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
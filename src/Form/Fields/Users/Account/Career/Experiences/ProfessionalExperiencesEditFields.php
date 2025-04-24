<?php

    namespace App\Form\Fields\Users\Account\Career\Experiences;

    use Symfony\Component\Validator\Constraints as Assert;

    class ProfessionalExperiencesEditFields
    {
        #[Assert\Length(
            min: 3,
            max: 80
        )]
        private ?string $jobTitle = null;

        #[Assert\NotBlank]
        private ?array $jobField = null;

        #[Assert\NotBlank]
        private ?string $town = null;

        #[Assert\NotBlank]
        private ?string $enterpriseName = null;

        #[Assert\NotBlank]
        private ?\DateTimeImmutable $startDate = null;

        #[Assert\GreaterThan(
            propertyPath: 'startDate',
            message: 'Cette valeur doit être supérieur à la date de debut'
        )]
        private ?\DateTimeImmutable $endDate = null;

        #[Assert\NotBlank]
        private ?string $jobDescription = null;


        //setters
        public function setTown(?string $town): void
        {
            $this->town = $town;
        }

        public function setEndDate(?\DateTimeImmutable $endDate): void
        {
            $this->endDate = $endDate;
        }

        public function setEnterpriseName(?string $enterpriseName): void
        {
            $this->enterpriseName = $enterpriseName;
        }

        public function setJobDescription(?string $jobDescription): void
        {
            $this->jobDescription = $jobDescription;
        }

        public function setJobField(?array $jobField): void
        {
            $this->jobField = $jobField;
        }

        public function setJobTitle(?string $jobTitle): void
        {
            $this->jobTitle = $jobTitle;
        }

        public function setStartDate(?\DateTimeImmutable $startDate): void
        {
            $this->startDate = $startDate;
        }



        //getters
        public function getTown(): ?string
        {
            return $this->town;
        }

        public function getEndDate(): ?\DateTimeImmutable
        {
            return $this->endDate;
        }

        public function getEnterpriseName(): ?string
        {
            return $this->enterpriseName;
        }

        public function getJobDescription(): ?string
        {
            return $this->jobDescription;
        }

        public function getJobField(): ?array
        {
            return $this->jobField;
        }

        public function getJobTitle(): ?string
        {
            return $this->jobTitle;
        }

        public function getStartDate(): ?\DateTimeImmutable
        {
            return $this->startDate;
        }
    }
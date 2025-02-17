<?php

    namespace App\Form\Fields\Users\Account\Job;

    use Symfony\Component\Validator\Constraints as Assert;

    class JobSearchAreaFields
    {
        private ?string $jobArea = null;


        public function setJobArea(?string $jobArea): void
        {
            $this->jobArea = $jobArea;
        }

        public function getJobArea(): ?string
        {
            return $this->jobArea;
        }
    }
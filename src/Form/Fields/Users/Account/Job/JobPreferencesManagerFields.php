<?php

    namespace App\Form\Fields\Users\Account\Job;

    use Symfony\Component\Validator\Constraints as Assert;

    class JobPreferencesManagerFields
    {
        private ?array $jobPreferences = null;


        public function setJobPreferences(?array $jobPreferences): void
        {
            $this->jobPreferences = $jobPreferences;
        }

        public function getJobPreferences(): ?array
        {
            return $this->jobPreferences;
        }
    }
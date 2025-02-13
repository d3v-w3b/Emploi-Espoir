<?php

    namespace App\Form\Fields\Users\Account\Career\Presentation;

    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Symfony\Component\Validator\Constraints as Assert;

    class CvManagerFields
    {
        #[Assert\NotBlank]
        #[Assert\File(
            maxSize: '5M',
            mimeTypes: [
                'application/pdf',
                'application/msword', // DOC
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document' // DOCX
            ],
        )]
        private ?UploadedFile $cv = null;


        public function setCv(?UploadedFile $cv): void
        {
            $this->cv = $cv;
        }

        public function getCv(): ?UploadedFile
        {
            return $this->cv;
        }
    }
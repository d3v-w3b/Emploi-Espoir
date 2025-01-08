<?php

    namespace App\Form\Fields\Users\Profile\Avatar;

    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Symfony\Component\Validator\Constraints as Assert;

    class AvatarManagerFields
    {
        #[Assert\File(
            maxSize: '2M',
            mimeTypes: ['image.png', 'image/jpg', 'image/jpeg', 'image/jfif'],
            maxSizeMessage: 'La taille de l\'image ne doit pas dépasser 2Mo',
            mimeTypesMessage: 'Les extensions recommandées sont : .png, .jpg, .jpeg, .jfif'
        )]
        private ?UploadedFile $profilePic = null;


        public function setProfilePic(?UploadedFile $profilePic): void
        {
            $this->profilePic = $profilePic;
        }

        public function getProfilePic(): ?UploadedFile
        {
            return $this->profilePic;
        }
    }
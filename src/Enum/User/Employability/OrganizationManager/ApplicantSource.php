<?php

    namespace App\Enum\User\Employability\OrganizationManager;

    enum ApplicantSource: string
    {
        case SELF_APPLICATION = 'self-application';
        case HEADHUNTED = 'headhunted';
    }
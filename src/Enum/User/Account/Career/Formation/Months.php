<?php

    namespace App\Enum\User\Account\Career\Formation;

    enum Months: string
    {
        case January = 'January';
        case February = 'February';
        case March = 'March';
        case April = 'April';
        case May = 'May';
        case June = 'June';
        case July = 'July';
        case August = 'August';
        case September = 'September';
        case October = 'October';
        case November = 'November';
        case December = 'December';


        public function getLabel(): string
        {
            return match ($this) {
                self::January => 'Janvier',
                self::February => 'Février',
                self::March => 'Mars',
                self::April => 'Avril',
                self::May => 'Mai',
                self::June => 'Juin',
                self::July => 'Juillet',
                self::August => 'Août',
                self::September => 'Septembre',
                self::October => 'Octobre',
                self::November => 'Novembre',
                self::December => 'Décembre',
            };
        }
    }
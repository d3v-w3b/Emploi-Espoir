<?php

    namespace App\Enum\User\Account\Career\Formation;

    enum Months: int
    {
        case January = 1;
        case February = 2;
        case March = 3;
        case April = 4;
        case May = 5;
        case June = 6;
        case July = 7;
        case August = 8;
        case September = 9;
        case October = 10;
        case November = 11;
        case December = 12;


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
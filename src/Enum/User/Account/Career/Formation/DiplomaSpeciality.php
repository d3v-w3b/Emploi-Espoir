<?php

    namespace App\Enum\User\Account\Career\Formation;

    enum DiplomaSpeciality: string
    {
        // Sciences et Technologies
        case COMPUTER_SCIENCE = 'Informatique';
        case CIVIL_ENGINEERING = 'Génie Civil';
        case ELECTRICAL_ENGINEERING = 'Génie Électrique';
        case MECHANICAL_ENGINEERING = 'Génie Mécanique';
        case AGRONOMY = 'Agronomie';
        case BIOTECHNOLOGY = 'Biotechnologie';
        case ENVIRONMENTAL_SCIENCE = 'Sciences Environnementales';

        // Commerce et Gestion
        case ACCOUNTING = 'Comptabilité';
        case FINANCE = 'Finance';
        case MARKETING = 'Marketing';
        case MANAGEMENT = 'Gestion';
        case INTERNATIONAL_TRADE = 'Commerce International';
        case LOGISTICS = 'Logistique';

        // Droit et Sciences Politiques
        case LAW = 'Droit';
        case POLITICAL_SCIENCE = 'Sciences Politiques';
        case PUBLIC_ADMINISTRATION = 'Administration Publique';

        // Sciences Humaines et Sociales
        case SOCIOLOGY = 'Sociologie';
        case PSYCHOLOGY = 'Psychologie';
        case GEOGRAPHY = 'Géographie';
        case HISTORY = 'Histoire';
        case PHILOSOPHY = 'Philosophie';

        // Santé
        case MEDICINE = 'Médecine';
        case PHARMACY = 'Pharmacie';
        case NURSING = 'Infirmier/Infirmière';
        case PUBLIC_HEALTH = 'Santé Publique';

        // Éducation et Formation
        case TEACHER_TRAINING = 'Formation des Enseignants';
        case PEDAGOGY = 'Pédagogie';

        // Arts, Culture et Communication
        case COMMUNICATION = 'Communication';
        case JOURNALISM = 'Journalisme';
        case PERFORMING_ARTS = 'Arts du Spectacle';
        case DESIGN = 'Design';
        case FASHION = 'Mode';

        // Agriculture et Développement Rural
        case AGRICULTURAL_TECHNOLOGY = 'Technologie Agricole';
        case RURAL_DEVELOPMENT = 'Développement Rural';

        // Tourisme et Hôtellerie
        case TOURISM = 'Tourisme';
        case HOSPITALITY = 'Hôtellerie';

        // Autres domaines
        case TRANSPORT = 'Transport';
        case MINING = 'Mines et Géologie';
        case ENERGY = 'Énergie';

        public function getLabel(): string
        {
            return $this->value;
        }
    }
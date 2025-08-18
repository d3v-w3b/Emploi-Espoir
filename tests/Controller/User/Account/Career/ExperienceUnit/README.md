# Tests Unitaires PURS - Gestion des Expériences Professionnelles

## 📁 Structure
```
tests/Controller/User/Account/Career/ExperienceUnit/
├── ExperienceManagerControllerTest.php       # Tests gestion expériences
├── ExperienceEditControllerTest.php          # Tests modification expériences
├── ExperienceDeleteControllerTest.php        # Tests suppression expériences
└── README.md                                # Cette documentation
```

## 🚀 Exécution des Tests

Pour exécuter ces tests unitaires :
```bash
docker compose exec php bin/phpunit tests/Controller/User/Account/Career/ExperienceUnit --testdox
```

Pour un fichier spécifique :
```bash
docker compose exec php bin/phpunit tests/Controller/User/Account/Career/ExperienceUnit/ExperienceManagerControllerTest.php --testdox
```

## 🎯 Objectif des Tests

Ces tests unitaires **PURS** sont conçus pour :
- ✅ Tester la logique des expériences sans dépendances externes
- ⚠️ **RÉVÉLER les failles de propriété professionnelle** 
- 🔍 Valider les protections contre la falsification d'expériences
- 📊 Couvrir les fonctions critiques de gestion expériences

## 🚨 Tests qui DOIVENT Échouer (par design)

Ces tests révèlent les **FAILLES CRITIQUES PROFESSIONNELLES** :

### ExperienceManagerControllerTest
- `testExperienceOwnershipValidation()` - Révèle l'accès aux expériences d'autrui
- `testExperienceVerificationSecurity()` - Révèle l'absence de vérification employeur
- `testExperienceOverlapValidation()` - Révèle les expériences simultanées impossibles

### ExperienceEditControllerTest  
- `testExperienceEditOwnership()` - Révèle la modification d'expériences d'autrui
- `testExperienceDateCoherence()` - Révèle les dates professionnelles incohérentes
- `testExperienceCompanyValidation()` - Révèle la validation entreprise insuffisante

### ExperienceDeleteControllerTest
- `testExperienceDeleteOwnership()` - Révèle la suppression d'expériences d'autrui
- `testExperienceReferenceIntegrity()` - Révèle la suppression avec références CV actives

## 🔒 Failles de Sécurité Révélées

### 1. FAILLE PROFESSIONNELLE - Expériences d'Autrui Modifiables
```php
// PROBLÈME CRITIQUE : User A peut falsifier expérience User B
$experience = $repository->find(123); // Expérience de User B
$experience->setCompany('Google'); // ⚠️ FALSIFICATION CRITIQUE
$experience->setPosition('Senior Developer'); // ⚠️ FAILLE PROFESSIONNELLE
```

### 2. Absence de Vérification Employeur
```php
// PROBLÈME : Entreprises fictives acceptées
$experience->setCompany('Entreprise Inexistante SAS'); // ⚠️ Non vérifiée
$experience->setSalary(999999); // ⚠️ Salaire non vérifié
```

### 3. Chevauchement d'Expériences Impossibles
```php
// PROBLÈME : Expériences simultanées acceptées
$exp1->setPeriod('2024-01-01', '2024-12-31'); // Google
$exp2->setPeriod('2024-06-01', '2024-12-31'); // Microsoft // ⚠️ Impossible
```

## 📊 Résultats Attendus

- **Tests vont ÉCHOUER** révélant les failles de gestion expériences
- **Impact business** : CRITIQUE - CV falsifiés, compétences surévaluées
- **Impact recrutement** : Données professionnelles non fiables

## 🎯 Actions Recommandées

1. **URGENT** : Vérifier propriété avant TOUT accès expérience
2. **URGENT** : Implémenter vérification entreprises (SIRET, existence)
3. **URGENT** : Valider non-chevauchement des périodes d'emploi
4. **URGENT** : Ajouter système de validation par employeurs

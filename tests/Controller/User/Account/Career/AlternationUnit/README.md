# Tests Unitaires PURS - Gestion des Alternances

## 📁 Structure
```
tests/Controller/User/Account/Career/AlternationUnit/
├── AlternationManagerControllerTest.php      # Tests gestion alternances
├── AlternationEditControllerTest.php         # Tests modification alternances
├── AlternationDeleteControllerTest.php       # Tests suppression alternances
└── README.md                                # Cette documentation
```

## 🚀 Exécution des Tests

Pour exécuter ces tests unitaires :
```bash
docker compose exec php bin/phpunit tests/Controller/User/Account/Career/AlternationUnit --testdox
```

Pour un fichier spécifique :
```bash
docker compose exec php bin/phpunit tests/Controller/User/Account/Career/AlternationUnit/AlternationManagerControllerTest.php --testdox
```

## 🎯 Objectif des Tests

Ces tests unitaires **PURS** sont conçus pour :
- ✅ Tester la logique des alternances sans dépendances externes
- ⚠️ **RÉVÉLER les failles de propriété et intégrité** 
- 🔍 Valider les protections contre la manipulation des contrats
- 📊 Couvrir les fonctions critiques de gestion alternances

## 🚨 Tests qui DOIVENT Échouer (par design)

Ces tests révèlent les **FAILLES CRITIQUES MÉTIER** :

### AlternationManagerControllerTest
- `testAlternationOwnershipValidation()` - Révèle l'accès aux alternances d'autrui
- `testAlternationContractValidation()` - Révèle l'absence de validation contrats
- `testAlternationDuplicateValidation()` - Révèle les alternances multiples simultanées

### AlternationEditControllerTest  
- `testAlternationEditOwnership()` - Révèle la modification d'alternances d'autrui
- `testAlternationDateValidation()` - Révèle les dates incohérentes
- `testAlternationCompanyValidation()` - Révèle la validation entreprise insuffisante

### AlternationDeleteControllerTest
- `testAlternationDeleteOwnership()` - Révèle la suppression d'alternances d'autrui
- `testAlternationReferentialIntegrity()` - Révèle la suppression avec références actives
- `testAlternationCurrentProtection()` - Révèle la suppression d'alternances en cours

## 🔒 Failles de Sécurité Révélées

### 1. FAILLE PROPRIÉTÉ - Alternances d'Autrui Manipulables
```php
// PROBLÈME CRITIQUE : User A peut modifier alternance User B
$alternation = $repository->find(789); // Alternance de User B
$alternation->setCompany('Entreprise modifiée'); // ⚠️ FAILLE CRITIQUE
```

### 2. Validation Métier Insuffisante
```php
// PROBLÈME : Dates incohérentes acceptées
$alternation->setStartDate('2025-12-01');
$alternation->setEndDate('2025-01-01'); // ⚠️ Fin avant début
```

### 3. Intégrité Référentielle Non Respectée
```php
// PROBLÈME : Suppression alternance encore référencée
$alternation->delete(); // ⚠️ Encore dans CV, certifications, etc.
```

## 📊 Résultats Attendus

- **Tests vont ÉCHOUER** révélant les failles de gestion alternances
- **Impact business** : CRITIQUE - Contrats falsifiés, données incohérentes
- **RGPD** : Violation accès données professionnelles

## 🎯 Actions Recommandées

1. **URGENT** : Vérifier propriété avant TOUT accès alternance
2. **URGENT** : Valider cohérence dates début/fin
3. **URGENT** : Vérifier références avant suppression
4. **URGENT** : Protéger alternances en cours contre suppression

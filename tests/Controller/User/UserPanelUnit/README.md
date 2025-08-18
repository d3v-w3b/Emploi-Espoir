# Tests Unitaires PURS - Dashboard et Tableau de Bord

## 📁 Structure
```
tests/Controller/User/UserPanelUnit/
├── DashboardControllerTest.php               # Tests accès données dashboard
└── README.md                                # Cette documentation
```

## 🚀 Exécution des Tests

Pour exécuter ces tests unitaires :
```bash
docker compose exec php bin/phpunit tests/Controller/User/UserPanelUnit --testdox
```

Pour un fichier spécifique :
```bash
docker compose exec php bin/phpunit tests/Controller/User/UserPanelUnit/DashboardControllerTest.php --testdox
```

## 🎯 Objectif des Tests

Ces tests unitaires **PURS** sont conçus pour :
- ✅ Tester la logique du dashboard sans dépendances externes
- ⚠️ **RÉVÉLER les failles d'accès aux données sensibles** 
- 🔍 Valider les protections contre l'exposition de données
- 📊 Couvrir les 4 fonctions moyennes du dashboard

## 🚨 Tests qui DOIVENT Échouer (par design)

Ces tests sont **volontairement conçus pour échouer** afin de révéler les problèmes **MOYENS** :

### DashboardControllerTest (12 asserts)
- `testDashboardSensitiveDataAccessControl()` - Révèle l'exposition de données sensibles
- `testDashboardSearchFiltersSecurity()` - Révèle les filtres non sécurisés
- `testDashboardCacheSecurityValidation()` - Révèle le cache mal protégé
- `testDashboardPaginationSecurityValidation()` - Révèle la pagination manipulable

## 🔒 Failles de Sécurité Révélées

### 1. Exposition de Données Sensibles
```php
// PROBLÈME : Utilisateur basique accède aux salaires
$salaries = $dashboard->getUserSalaries(); // ⚠️ Données confidentielles
$finances = $dashboard->getOrgFinances(); // ⚠️ Données financières
```

### 2. Filtres de Recherche Non Sécurisés
```php
// PROBLÈME : Injection dans les filtres
$filter = "org_id=999 OR 1=1"; // ⚠️ SQL Injection
$path = "../../../admin/data.json"; // ⚠️ Path traversal
```

### 3. Cache Mal Protégé
```php
// PROBLÈME : Cache expose données d'autres utilisateurs
$cached = $cache->get('user_456_data'); // ⚠️ Cross-user data
$admin = $cache->get('admin_stats'); // ⚠️ Privilege leak
```

### 4. Pagination Manipulable
```php
// PROBLÈME : Paramètres pagination contournables
$page = -1; // ⚠️ Page négative
$limit = 999999; // ⚠️ Limite excessive
```

## 📊 Résultats Attendus

- **4 tests vont ÉCHOUER** révélant 4 failles moyennes
- **12 assertions vont révéler** les protections d'accès manquantes
- **Impact sécurité** : MOYEN - Exposition données, manipulation filtres

## 🎯 Actions Recommandées

1. **IMPORTANT** : Filtrer données par utilisateur/rôle
2. **IMPORTANT** : Sécuriser les filtres de recherche (validation/échappement)
3. **IMPORTANT** : Isoler le cache par utilisateur
4. **IMPORTANT** : Valider les paramètres de pagination
# Tests Unitaires PURS - Sécurité de Navigation

## 📁 Structure
```
tests/Controller/User/NavigationSecurityUnit/
├── NavigationSecurityTest.php                # Tests sécurité navigation
└── README.md                                # Cette documentation
```

## 🚀 Exécution des Tests

Pour exécuter ces tests unitaires :
```bash
docker compose exec php bin/phpunit tests/Controller/User/NavigationSecurityUnit --testdox
```

Pour un fichier spécifique :
```bash
docker compose exec php bin/phpunit tests/Controller/User/NavigationSecurityUnit/NavigationSecurityTest.php --testdox
```

## 🎯 Objectif des Tests

Ces tests unitaires **PURS** sont conçus pour :
- ✅ Tester la sécurité de navigation sans dépendances externes
- ⚠️ **RÉVÉLER les failles de navigation et routage** 
- 🔍 Valider les protections contre phishing, traversée, manipulation
- 📊 Couvrir les 4 fonctions moyennes de navigation sécurisée

## 🚨 Tests qui DOIVENT Échouer (par design)

Ces tests sont **volontairement conçus pour échouer** afin de révéler les problèmes **MOYENS** :

### NavigationSecurityTest (12 asserts)
- `testRedirectUrlManipulationSecurity()` - Révèle les redirections malveillantes
- `testPathTraversalNavigationSecurity()` - Révèle la traversée de chemins
- `testRoleBasedNavigationAccessControl()` - Révèle l'accès non autorisé
- `testNavigationParameterValidationSecurity()` - Révèle les paramètres manipulables

## 🔒 Failles de Sécurité Révélées

### 1. Redirections Malveillantes (Phishing)
```php
// PROBLÈME : Redirections vers sites externes
$redirect = 'http://evil.com/phishing'; // ⚠️ Phishing externe
$redirect = '//attacker.com/steal-data'; // ⚠️ Protocole relatif
$redirect = 'javascript:alert("XSS")'; // ⚠️ JavaScript malveillant
```

### 2. Traversée de Chemins
```php
// PROBLÈME : Accès fichiers système
$path = '../../../etc/passwd'; // ⚠️ Traversée Unix
$path = '..\\..\\..\\windows\\system32\\config\\sam'; // ⚠️ Traversée Windows
$path = 'file:///etc/shadow'; // ⚠️ Protocole file
```

### 3. Contrôle d'Accès par Rôle Défaillant
```php
// PROBLÈME : Accès non autorisé selon rôle
if ($userRole === 'USER') {
    $this->accessAdminPanel(); // ⚠️ Utilisateur accède admin
}
```

### 4. Paramètres URL Manipulables
```php
// PROBLÈME : Injection dans paramètres navigation
$userId = "-1 OR 1=1"; // ⚠️ SQL Injection
$page = '<script>alert("XSS")</script>'; // ⚠️ XSS injection
$filter = '../../admin/config.php'; // ⚠️ File inclusion
```

## 📊 Résultats Attendus

- **4 tests vont ÉCHOUER** révélant 4 failles moyennes
- **12 assertions vont révéler** les protections de navigation manquantes
- **Impact sécurité** : MOYEN - Phishing, accès fichiers, escalade privilèges

## 🎯 Actions Recommandées

1. **IMPORTANT** : Valider les URLs de redirection (whitelist domaines)
2. **IMPORTANT** : Bloquer la traversée de chemins (path normalization)
3. **IMPORTANT** : Vérifier les rôles à chaque navigation
4. **IMPORTANT** : Valider tous les paramètres URL (type, format, range)
# Tests Unitaires PURS - Gestion des Paramètres Utilisateur

## 📁 Structure
```
tests/Controller/User/Profile/SettingsUnit/
├── SettingsManagerControllerTest.php         # Tests gestion paramètres
├── SettingsEditControllerTest.php            # Tests modification paramètres
├── SettingsDeleteControllerTest.php          # Tests suppression compte
└── README.md                                # Cette documentation
```

## 🚀 Exécution des Tests

```bash
docker compose exec php bin/phpunit tests/Controller/User/Profile/SettingsUnit --testdox
```

## 🎯 Objectif des Tests

Tests unitaires **PURS** pour révéler les failles critiques dans la gestion des paramètres utilisateur.

## 🚨 Failles Révélées

- `testEmailChangeSecurityValidation()` - Révèle les changements d'email non sécurisés
- `testAccountDeletionSecurityChecks()` - Révèle la suppression de compte sans vérifications
- `testEmailTokenSecurityValidation()` - Révèle les tokens email faibles

## 🔒 Problèmes Critiques

```php
// FAILLE : Changement email permet usurpation
$user->setEmail('admin@system.com'); // ⚠️ Email admin usurpé
$user->setEmail('victim@company.com'); // ⚠️ Email d'autrui usurpé

// FAILLE : Suppression compte sans vérifications
$user->delete(); // ⚠️ Compte avec commandes actives supprimé
```

## 🎯 Actions

1. **URGENT** : Vérifier propriété email avant changement
2. **URGENT** : Valider suppression compte (dettes, commandes)
3. **URGENT** : Sécuriser tokens email (cryptographiquement sûrs)
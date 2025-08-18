# Tests Unitaires PURS - Gestion des Langues

## 📁 Structure
```
tests/Controller/User/Account/Career/LanguageUnit/
├── LanguageManagerControllerTest.php         # Tests gestion langues
├── LanguageEditControllerTest.php            # Tests modification langues
├── LanguageDeleteControllerTest.php          # Tests suppression langues
└── README.md                                # Cette documentation
```

## 🚀 Exécution des Tests

```bash
docker compose exec php bin/phpunit tests/Controller/User/Account/Career/LanguageUnit --testdox
```

## 🎯 Objectif des Tests

Tests unitaires **PURS** pour révéler les failles de gestion des compétences linguistiques.

## 🚨 Failles Révélées

- `testLanguageOwnershipValidation()` - Révèle l'accès aux langues d'autrui
- `testLanguageLevelValidation()` - Révèle l'absence de validation niveaux
- `testLanguageCertificationSecurity()` - Révèle les certifications non vérifiées

## 🔒 Problèmes Critiques

```php
// FAILLE : User A modifie langues User B
$language = $repository->find(456); // Langue de User B
$language->setLevel('Native'); // ⚠️ Falsification niveau
```

## 🎯 Actions

1. **URGENT** : Vérifier propriété avant accès
2. **URGENT** : Valider niveaux selon standards (CECR)
3. **URGENT** : Vérifier certifications linguistiques
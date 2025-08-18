# Tests Unitaires PURS - Gestion des Liens Externes

## 📁 Structure
```
tests/Controller/User/Account/Career/ExternalLinksUnit/
├── ExternalLinksManagerControllerTest.php    # Tests gestion liens externes
├── ExternalLinksEditControllerTest.php       # Tests modification liens
├── ExternalLinksDeleteControllerTest.php     # Tests suppression liens
└── README.md                                # Cette documentation
```

## 🚀 Exécution des Tests

```bash
docker compose exec php bin/phpunit tests/Controller/User/Account/Career/ExternalLinksUnit --testdox
```

## 🎯 Objectif des Tests

Tests unitaires **PURS** pour révéler les failles de gestion des liens externes (Portfolio, LinkedIn, etc.).

## 🚨 Failles Révélées

- `testExternalLinksOwnershipValidation()` - Révèle l'accès aux liens d'autrui
- `testExternalLinksUrlValidation()` - Révèle l'absence de validation URLs
- `testExternalLinksMaliciousValidation()` - Révèle les liens malveillants acceptés

## 🔒 Problèmes Critiques

```php
// FAILLE : URLs malveillantes acceptées
$link->setUrl('javascript:alert("XSS")'); // ⚠️ JavaScript malveillant
$link->setUrl('http://phishing-site.com'); // ⚠️ Site de phishing
```

## 🎯 Actions

1. **URGENT** : Vérifier propriété avant accès
2. **URGENT** : Valider format URLs (whitelist protocoles)
3. **URGENT** : Bloquer domaines malveillants

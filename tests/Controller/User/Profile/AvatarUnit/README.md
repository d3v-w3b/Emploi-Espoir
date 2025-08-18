# Tests Unitaires PURS - Gestion des Avatars

## 📁 Structure
```
tests/Controller/User/Profile/AvatarUnit/
├── AvatarEditControllerTest.php              # Tests modification avatar
├── AvatarUploadControllerTest.php            # Tests upload avatar
├── AvatarDeleteControllerTest.php            # Tests suppression avatar
└── README.md                                # Cette documentation
```

## 🚀 Exécution des Tests

```bash
docker compose exec php bin/phpunit tests/Controller/User/Profile/AvatarUnit --testdox
```

## 🎯 Objectif des Tests

Tests unitaires **PURS** pour révéler les failles de sécurité dans la gestion des avatars.

## 🚨 Failles Révélées

- `testAvatarUploadSecurityValidation()` - Révèle les uploads de fichiers malveillants
- `testAvatarAccessControlSecurity()` - Révèle l'accès aux avatars d'autrui
- `testAvatarStorageSecurityValidation()` - Révèle le stockage non sécurisé

## 🔒 Problèmes Critiques

```php
// FAILLE : Fichiers malveillants uploadés comme avatar
$avatar = 'script.php'; // ⚠️ Script PHP déguisé
$avatar = 'virus.exe'; // ⚠️ Fichier exécutable
$avatarPath = '/uploads/user_456/avatar.jpg'; // ⚠️ Avatar d'autrui accessible
```

## 🎯 Actions

1. **URGENT** : Valider type/taille fichiers avatar
2. **URGENT** : Vérifier propriété avant accès avatar
3. **URGENT** : Stockage sécurisé (hors web root)
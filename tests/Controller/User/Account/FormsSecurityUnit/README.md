# Tests Unitaires PURS - Sécurité des Formulaires

## 📁 Structure
```
tests/Controller/User/Account/FormsSecurityUnit/
├── FormsInjectionSecurityTest.php            # Tests injection dans formulaires
└── README.md                                # Cette documentation
```

## 🚀 Exécution des Tests

Pour exécuter ces tests unitaires :
```bash
docker compose exec php bin/phpunit tests/Controller/User/Account/FormsSecurityUnit --testdox
```

Pour un fichier spécifique :
```bash
docker compose exec php bin/phpunit tests/Controller/User/Account/FormsSecurityUnit/FormsInjectionSecurityTest.php --testdox
```

## 🎯 Objectif des Tests

Ces tests unitaires **PURS** sont conçus pour :
- ✅ Tester la validation des formulaires sans dépendances externes
- ⚠️ **RÉVÉLER les failles d'injection dans les formulaires** 
- 🔍 Valider les protections contre XSS, HTML, débordement
- 📊 Couvrir les 4 fonctions moyennes de sécurité formulaires

## 🚨 Tests qui DOIVENT Échouer (par design)

Ces tests sont **volontairement conçus pour échouer** afin de révéler les problèmes **MOYENS** :

### FormsInjectionSecurityTest (12 asserts)
- `testFormsXSSInjectionSecurity()` - Révèle l'absence de protection XSS
- `testFormsHTMLInjectionSecurity()` - Révèle l'absence de filtrage HTML
- `testFormsDataOverflowSecurity()` - Révèle l'absence de limitation taille
- `testFormsSpecialCharactersInjectionSecurity()` - Révèle l'absence de validation caractères

## 🔒 Failles de Sécurité Révélées

### 1. Injection XSS dans les Formulaires
```php
// PROBLÈME : Script malveillant accepté
$firstName = '<script>alert("XSS")</script>'; // ⚠️ XSS non filtré
$aboutMe = '<img src=x onerror=alert("XSS")>'; // ⚠️ Image malveillante
```

### 2. Injection HTML Malveillant
```php
// PROBLÈME : HTML dangereux accepté
$jobTitle = '<iframe src="javascript:alert(1)"></iframe>'; // ⚠️ Iframe malveillant
$description = '<object data="data:text/html,<script>alert(1)</script>">'; // ⚠️ Object malveillant
```

### 3. Débordement de Données (DoS)
```php
// PROBLÈME : Données surdimensionnées acceptées
$bio = str_repeat('A', 1000000); // ⚠️ 1MB de données
$skills = str_repeat('SKILL,', 100000); // ⚠️ 600KB de compétences
```

### 4. Caractères Spéciaux Dangereux
```php
// PROBLÈME : Caractères de contrôle acceptés
$email = 'test@domain.com%0D%0ABcc:attacker@evil.com'; // ⚠️ Header injection
$phone = '+33123456789\n\r<script>alert(1)</script>'; // ⚠️ Caractères contrôle
```

## 📊 Résultats Attendus

- **4 tests vont ÉCHOUER** révélant 4 failles moyennes
- **12 assertions vont révéler** les validations manquantes
- **Impact sécurité** : MOYEN - XSS, injection HTML, DoS, header injection

## 🎯 Actions Recommandées

1. **IMPORTANT** : Implémenter filtrage XSS (htmlspecialchars, CSP)
2. **IMPORTANT** : Valider et échapper le HTML (whitelist tags)
3. **IMPORTANT** : Limiter la taille des données (max length, file size)
4. **IMPORTANT** : Filtrer les caractères spéciaux (regex validation)
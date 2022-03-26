# Liste de tous les attributs possibles pour les propriétés des modèles

Les **filtres d'assainissement** sont définis dans [/core/SanitizerFilters.php](/core/SanitizerFilters.php).

Les **règles de validation** sont définies dans [/core/ValidatorRules.php](/core/ValidatorRules.php).

Les **attributs par défaut** de chaque type sont définis dans [/core/assets/default-attributes/](/core/assets/default-attributes/).

## Type

- Clé : `type`
- Valeurs acceptées : `string`, `integer`, `float`, `date`, `boolean`, `object`
- Description : _Définit le type de la propriété. Lors de l'assainissement, la propriété sera typée avec la méthode `cast` de [/core/SanitizerFilters.php](/core/SanitizerFilters.php). Lors de la validation, sa valeur sera vérifiée avec la méthode `type` de [/core/ValidatorRules.php](/core/ValidatorRules.php)._

## Caractéristiques

### Clé primaire

- Clé : `primary_key`
- Valeurs acceptées : `true`, `false` (défaut)
- Description : _Si la propriété est une clé primaire._

### Unique

- Clé : `unique`
- Valeurs acceptées : `true`, `false` (défaut)
- Description : _Si la valeur de la propriété est unique en BDD._

### Modifiable

- Clé : `fillable`
- Valeurs acceptées : `true` (défaut), `false`
- Description : _Si la valeur de la propriété est modifiable en BDD._

### Caché

- Clé : `hidden`
- Valeurs acceptées : `true`, `false` (défaut)
- Description : _Cet attribut défini à `true`, cette propriété ne sera pas renvoyée depuis la BDD._

### Recherche

- Clé : `search`
- Valeurs acceptées : `true`, `false` (défaut)
- Description : _Si cette colonne doit être parcourue lors d'une recherche._

## Valeur

Pour la gestion du formulaire d'ajout/modification et la validation du modèle avant insertion/modification en base de données

### Défaut

- Clé : `default`
- Valeurs acceptées : Toutes
- Description : _Valeur par défaut de la propriété._

### Requis

- Clé : `required`
- Valeurs acceptées : `true`, `false` (défaut)
- Description : _Si la propriété doit être renseignée._
- Emplacement : [/core/ValidatorRules.php](/core/ValidatorRules.php)

### Minimum

- Clé : `min`
- Valeurs acceptées : Nombre entier
- Description : _Valeur numérique minimale de la propriété._
- Emplacement : [/core/ValidatorRules.php](/core/ValidatorRules.php)

### Maximum

- Clé : `max`
- Valeurs acceptées : Nombre entier
- Description : _Valeur numérique maximale de la propriété._
- Emplacement : [/core/ValidatorRules.php](/core/ValidatorRules.php)

### Longueur minimale

- Clé : `min_length`
- Valeurs acceptées : Nombre entier
- Description : _Longueur minimale de la chaîne de caractères._
- Emplacement : [/core/ValidatorRules.php](/core/ValidatorRules.php)

### Longueur maximale

- Clé : `max_length`
- Valeurs acceptées : Nombre entier
- Description : _Longueur maximale de la chaîne de caractères._
- Emplacement : [/core/ValidatorRules.php](/core/ValidatorRules.php)

### Longueur exacte

- Clé : `length`
- Valeurs acceptées : Nombre entier
- Description : _Longueur exacte de la chaîne de caractères._
- Emplacement : [/core/ValidatorRules.php](/core/ValidatorRules.php)

### Incluse dans une liste

- Clé : `in`
- Valeurs acceptées : Tableau de valeurs
- Description : _La valeur doit correspondre à l'une de celles contenue dans le tableau._
- Exemple : `'in' => [1 , 2, 3]`
- Emplacement : [/core/ValidatorRules.php](/core/ValidatorRules.php)

### Adresse courriel

- Clé : `email`
- Valeurs acceptées : `true`, `false` (défaut)
- Description : _Format d'une adresse courriel._
- Emplacement : [/core/ValidatorRules.php](/core/ValidatorRules.php)

### Adresse URL

- Clé : `url`
- Valeurs acceptées : `true`, `false` (défaut)
- Description : _Format d'une adresse URL._
- Emplacement : [/core/ValidatorRules.php](/core/ValidatorRules.php)

### Numéro de téléphone

- Clé : `phone`
- Valeurs acceptées : `true`, `false` (défaut)
- Description : _Format d'un numéro de téléphone._
- Emplacement : [/core/ValidatorRules.php](/core/ValidatorRules.php)

### Expression régulière

- Clé : `regex`
- Valeurs acceptées : Chaîne de caractères d'une expression régulière.
- Description : _Format d'une expression régulière._
- Emplacement : [/core/ValidatorRules.php](/core/ValidatorRules.php)

## Assainissement

Pour nettoyer les données avant de les traiter et/ou de passer à la validation.

### Capitale

- Clé : `capitalize`
- Valeurs acceptées : `true`, `false` (défaut)
- Description : _Met en majuscule la première lettre d'une chaîne._
- Emplacement : [/core/SanitizerFilters.php](/core/SanitizerFilters.php)

### Nombre

- Clé : `digit`
- Valeurs acceptées : `true`, `false` (défaut)
- Description : _Ne renvoie que les caractères numériques d'une chaîne._
- Emplacement : [/core/SanitizerFilters.php](/core/SanitizerFilters.php)

### Échapper les caractères spéciaux

- Clé : `escape`
- Valeurs acceptées : `true`, `false` (défaut)
- Description : _Supprime les balises et supprime ou encode les caractères spéciaux._
- Emplacement : [/core/SanitizerFilters.php](/core/SanitizerFilters.php)

### Formater une date

- Clé : `format_date`
- Valeurs acceptées : Tableau comportant le format de la source et de la cible (string[]).
- Description : _Change le format d'une date._
- Emplacement : [/core/SanitizerFilters.php](/core/SanitizerFilters.php)

### Minuscules

- Clé : `lowercase`
- Valeurs acceptées : `true`, `false` (défaut)
- Description : _Met tous les caractères en minuscules._
- Emplacement : [/core/SanitizerFilters.php](/core/SanitizerFilters.php)

### Supprimer les balises

- Clé : `strip_tags`
- Valeurs acceptées : `true`, `false` (défaut)
- Description : _Supprime les balises HTML et PHP d'une chaîne._
- Emplacement : [/core/SanitizerFilters.php](/core/SanitizerFilters.php)

### Supprimer les espaces

- Clé : `trim`
- Valeurs acceptées : Chaîne de caractères comportant les caractères à supprimer.
- Description : _Supprime les espaces en début et fin de chaîne._
- Emplacement : [/core/SanitizerFilters.php](/core/SanitizerFilters.php)

### Majuscules

- Clé : `uppercase`
- Valeurs acceptées : `true`, `false` (défaut)
- Description : _Met tous les caractères en majuscules._
- Emplacement : [/core/SanitizerFilters.php](/core/SanitizerFilters.php)

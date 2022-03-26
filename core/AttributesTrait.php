<?php

namespace Core;

/**
 * Ce trait gère les attributs des propriétés d'une classe.
 * 
 * Il est utilisé par AbstractModel, où il permet de définir
 * les caractéristiques des colonnes des tables (type, taille, etc..).
 */
trait AttributesTrait
{
    /**
     * Tableau associatif d'attributs relatifs aux propriétés d'une table.
     * 
     * @example:  [
     *  ['id'] => [
     *      ['type'  => 'integer'],
     *      ['digit' => true]
     *  ],
     *  ['nom'] => [
     *      ['type'  => 'string'],
     *      ['trim' => true]
     *  ]
     * ]
     * 
     * @var array[]
     */
    protected $attributes;

    /**
     * Définit les attributs des propriétés d'un modèle.
     * 
     * Cette méthode est généralement appelée dans le constructeur.
     *
     * @param  string $entityName (facultatif pour l'entité)
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setAttributes($entityName = null)
    {
        // Récupération des attributs depuis `app\models\properties`
        $entityName = $entityName ?: \App\Functions\ClassUtils::getName($this, false);
        $propsFile = path('models') . "/properties/{$entityName}Properties.php";
        if (!is_readable($propsFile)) {
            throw new \InvalidArgumentException("Le fichier <em>$propsFile</em> est introuvable");
        }

        // Définition des attributs de chaque propriété en récupérant leur valeur par défaut
        $this->attributes = array_map(function ($propAttributes) {
            if (empty($propAttributes['type'])) {
                throw new \InvalidArgumentException("Une propriété nécessite un type pour être définie.");
            }

            // Récupération des attributs par défaut du type concerné depuis `core/assets/default-attributes`
            $typeFile = path('assets') . "/default-attributes/{$propAttributes['type']}.php";
            if ($propAttributes['type'] === 'common' || !file_exists($typeFile)) {
                throw new \InvalidArgumentException("Ce type de propriété n'est pas défini: '{$propAttributes['type']}'");
            }

            return array_merge(include($typeFile), $propAttributes);
        }, require $propsFile);

        return $this;
    }

    /**
     * Renvoie la liste des propriétés avec leurs attributs.
     * 
     * Le paramètre `$filter` permet de ne renvoyer que les propriétés ayant les attributs aux valeurs demandées.
     *
     * @param  string|mixed[] $filter ex: `'required'`, `['required', 'fillable' => false]`
     * @return string[]
     */
    public function attributes($filter = null)
    {
        if (!$filter) {
            return $this->attributes;
        }

        $attrList = [];
        if (is_array($filter)) {
            return $this->attributesByFilterArray($filter);
        }
        if (is_string($filter)) {
            foreach ($this->attributes as $propName => $attribute) {
                if (!empty($attribute[$filter])) {
                    $attrList[] = $propName;
                }
            }
        }

        return $attrList;
    }

    /**
     * Renvoie la liste des propriétés avec leurs attributs selon des filtres.
     * 
     * @param  string|mixed[] $filter ex: `'required'`, `['required', 'fillable' => false]`
     * @return string[]
     */
    public function attributesByFilterArray($filter)
    {
        $attrList = [];
        foreach ($this->attributes as $propName => $attribute) {
            foreach ($filter as $key => $val) {
                if ((is_string($key) &&
                        ($val === (isset($attribute[$key]) ? $attribute[$key] : false)))
                    || (is_int($key) && !empty($attribute[$val]))
                ) {
                    $attrList[] = $propName;
                }
            }
        }
        return $attrList;
    }

    /**
     * Renvoie une entité du modèle avec ses valeurs par défaut.
     *
     * @return object
     */
    public function getDefaultEntity()
    {
        $entityName = "\App\Entities\\" . $this->entity;
        $entity = new $entityName();
        foreach ($this->attributes as $prop => $attributes) {
            if (isset($attributes['default'])) {
                $entity->$prop = $attributes['default'];
            }
        }
        return $entity;
    }
}

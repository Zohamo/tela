<?php

namespace Core;

/**
 * Ce trait ajoute la propriété `$properties` à une classe.
 * `$properties` est un tableau associatif de données.
 * 
 * Ce trait contient des méthodes utiles à la gestion des propriétés.
 */
trait PropertiesTrait
{
    use AttributesTrait, RelationshipTrait;

    /**
     * Propriétés de l'objet.
     * 
     * @var mixed[]
     */
    protected $properties = [];

    /**
     * Renvoie la liste des propriétés de l'objet.
     *
     * @return mixed[]
     */
    public function properties()
    {
        return $this->properties;
    }

    /**
     * Si l'objet possède la propriété demandée.
     *
     * @param  string $propName
     * @return bool
     */
    public function hasProperty($propName)
    {
        return in_array($propName, array_keys($this->attributes));
    }

    /**
     * Méthode magique pour récupérer la valeur d'une propriété.
     * 
     * Cette méthode est appelée pour lire des données depuis une propriété
     * inaccessible (protégée ou privée) ou non existante.
     * 
     * @example `$id = $model->id;`
     * 
     * @param  string $name Nom de la propriété.
     * @return mixed Valeur de la propriété.
     */
    public function __get($name)
    {
        if (isset($this->properties[$name])) {
            return $this->properties[$name];
        }
    }

    /**
     * Méthode magique pour définir une propriété.
     * 
     * Cette méthode est sollicitée lors de l'écriture de données vers une propriété
     * inaccessible (protégée ou privée) ou non existante.
     * 
     * @example `$model->id = 1;`
     * 
     * @param  string $name  Nom de la propriété.
     * @param  mixed  $value Valeur de la propriété.
     * @return void
     */
    public function __set($name, $value)
    {
        // D'abord on vérifie si cette entité possède un Setter défini
        $setter = "set" . ucfirst($name);
        if (method_exists($this, $setter)) {
            // La méthode `setNom_de_la_propriété($value)` existe: on l'utilise
            $this->$setter($value);
        } elseif ($this->hasProperty($name)) {
            $this->properties[$name] = $value;
        }
    }

    /**
     * Si l'entité possède une relation 'has-many' qui possède ce nom.
     * 
     * Sert principalement au Setter pour définir un tableau de données de la relation.
     * 
     * @param string $name
     * @return boolean
     */
    public function hasMany($name)
    {
        return in_array($name, array_keys($this->relationships));
    }
}

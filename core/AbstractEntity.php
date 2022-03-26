<?php

namespace Core;

/**
 * Entité de base, contient les outils nécéssaires pour alimenter
 * et récupérer les données d'un objet.
 */
abstract class AbstractEntity
{
    /**
     * Crée une instance d'AbstractEntity.
     * 
     * @param mixed[] $data
     * @return void
     */
    function __construct(array $data = [])
    {
        $this->hydrate($data);
    }

    /**
     * Définit les propriétés de l'entité.
     * 
     * @param mixed[] $data Tableau associatif de données
     * @return $this
     */
    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            $this->__set($key, $value);
        }
        return $this;
    }

    /**
     * Méthode magique pour récupérer la valeur d'une propriété.
     * 
     * On recherche d'abord si une méthode `$name()` existe,
     * ensuite si une propriété `$name` existe.
     * 
     * @param string $name Nom de la propriété
     * @return mixed Valeur de la propriété
     */
    public function __get($name)
    {
        if (method_exists($this, $name)) {
            return $this->$name();
        } elseif (property_exists($this, $name)) {
            return $this->$name;
        }
    }

    /**
     * Méthode magique pour définir la valeur d'une propriété.
     * 
     * Si une méthode du type `setNom_de_la_propriété()` n'existe pas,
     * on définit directement la propriété `$name` si elle existe.
     * 
     * @param string $name  Nom de la propriété
     * @param mixed  $value Valeur de la propriété
     * @return void
     */
    public function __set($name, $value)
    {
        $setter = "set" . ucfirst($name);
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } else {
            $this->$name = $value;
        }
    }
}

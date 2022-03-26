<?php

namespace Core;

/**
 * Assainisseur de données.
 * 
 * @see: core/SanitizerFilters.php
 */
class Sanitizer
{
    /**
     * Tableau associatif de données.
     * 
     * @example: [['id' => 23], ['nom' => "Roger"]]
     * 
     * @var mixed[]
     */
    protected $data;

    /**
     * Tableau associatif contenant les attributs de chaque donnée.
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
     * Mode strict: À `true`, les données non présentes dans le tableau
     * des attributs seront supprimées lors de `sanitize()`.
     * 
     * @var boolean
     */
    protected $strict;

    /**
     * Alimente les données de l'objet à instancier.
     *
     * @param  mixed[] $data Tableau associatif des données avec leur valeur.
     * @param  array[] $dataAttributes Tableau associatif des données avec leurs attributs.
     * @param  boolean $strict (facultatif) À `true`, les données non présentes dans le tableau
     *                  des attributs seront supprimées lors de `sanitize()`.
     */
    public function __construct(array $data, array $attributes, $strict = false)
    {
        $this->data = $data;
        $this->attributes = $attributes;
        $this->strict = $strict;
    }

    /**
     * Assainit les données et les renvoie.
     *
     * @param  array[] $optAttributes (facultatif) Tableau associatif d'attributs additionnels.
     * @return mixed[] Données assainies.
     */
    public function sanitize(array $optAttributes = [])
    {
        foreach ($this->data as $name => $value) {
            if (empty($this->attributes[$name]) && empty($optAttributes[$name]) && $this->strict) {
                unset($this->data[$name]);
                continue;
            }

            // Récupération des attributs de la donnée
            $propAttributes = empty($this->attributes[$name]) ? [] : $this->attributes[$name];

            // Ajout des filtres additionnels
            if (!empty($optAttributes[$name])) {
                $propAttributes = array_merge($propAttributes, $optAttributes[$name]);
            }

            // Assainissement
            $this->data[$name] = $this->sanitizeProperty($value, $propAttributes);
        }

        return $this->data;
    }

    /**
     * Assainit une donnée selon ses attributs.
     *
     * @param  mixed   $value Valeur à assainir.
     * @param  mixed[] $attributes Attributs de la donnée (filtres à appliquer).
     * @return mixed
     */
    public function sanitizeProperty($value, array $attributes = [])
    {
        foreach ($attributes as $filter => $filterValue) {
            if ($filter != 'type' && method_exists('Core\SanitizerFilters', $filter)) {
                if ($filterValue === false) {
                    continue;
                }
                $value = $filterValue === true
                    ? \Core\SanitizerFilters::$filter($value)
                    : \Core\SanitizerFilters::$filter($value, $filterValue);
            }
        }

        // On ne convertit le type de donnée qu'en dernier
        return \Core\SanitizerFilters::cast($value, $attributes['type']);
    }
}

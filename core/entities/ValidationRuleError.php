<?php

namespace Core\Entities;

/**
 * Objet renvoyé par une règle de validation qui n'est pas respectée.
 */
class ValidationRuleError
{
    /**
     * Valeur de la règle de validation.
     * 
     * @var mixed
     */
    public $ruleValue;

    /**
     * Message d'erreur.
     *
     * @var string
     */
    public $message;

    /**
     * Crée une instance de ValidationRuleError.
     *
     * @param mixed  $ruleValue Valeur de la règle de validation.
     * @param string $message   Message d'erreur.
     */
    public function  __construct($ruleValue, $message)
    {
        $this->ruleValue = $ruleValue;
        $this->message = $message;
    }
}

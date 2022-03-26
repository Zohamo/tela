<?php

namespace Core;

/**
 * Ce trait contient des méthodes utiles au QueryBuilder pour le débogage.
 */
trait QueryBuilderDebugTrait
{
    /**
     * Modes de débogage pour afficher la requête SQL exécutée.
     * 
     * @var string 'debug'(défaut)|'dieBefore'|'dieAfter'
     */
    private $debug;

    /**
     * Active le débogage de la requête à exécuter.
     *
     * @param  string $mode 'debug'(défaut),
     *                      'dieBefore': arrête le script avant l'éxécution de la requête,
     *                      'dieAfter': arrête le script après l'éxécution de la requête.
     * @return $this
     */
    public function debug($mode = 'debug')
    {
        $this->debug = $mode;
        return $this;
    }

    /**
     * Méthode à appeler avant l'exécution d'une requête.
     * 
     * Contient l'affichage de la requête pour débogage.
     *
     * @param  string $sql
     * @param  mixed[] $values
     * @param  string|null $className
     * @return void|exit
     */
    private function beforeExecute($query, $values, $className = null) // NOSONAR : Variables utilisées dans le fichier inclus
    {
        if ($this->debug) {
            require path('template') . "/components/debug-query.php";
            if ($this->debug === 'dieBefore') {
                exit(1);
            }
        }
    }

    /**
     * Méthode à appeler après l'exécution d'une requête.
     * 
     * Contient l'affichage du résultat de l'exécution de la requête
     * et la réinitialisation du QueryBuilder.
     *
     * @param  mixed $result
     * @return void|exit
     */
    private function afterExecute($result) // NOSONAR : Variables utilisées dans le fichier inclus
    {
        if ($this->debug) {
            require path('template') . "/components/debug-query-result.php";
            if ($this->debug === 'dieAfter') {
                exit(1);
            }
        }
        $this->reset();
    }
}

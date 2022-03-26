<?php

namespace App\Controllers;

use Core\AbstractController;
use Core\View;

class HomeController extends AbstractController
{
    /**
     * Liens à mettre en valeur dans la barre de navigation.
     * 
     * @var string[]
     */
    protected $activeLinks = ["home"];

    /**
     * Affiche la page d'accueil.
     * 
     * @route("/", methods={"GET"})
     */
    public function index()
    {
        View::render('home');
    }
}

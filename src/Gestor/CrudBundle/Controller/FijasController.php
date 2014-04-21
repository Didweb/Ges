<?php

namespace Gestor\CrudBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FijasController extends Controller
{
    public function indexAction()
    {
        return $this->render('GestorCrudBundle:Fijas:index.html.twig');
    }
}

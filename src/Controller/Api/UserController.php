<?php

namespace App\Controller\Api;

use App\Repository\UsuarioBDRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class UserController extends AbstractFOSRestController{

    /**
     * @Rest\Get(path="/usuarios")
     * @Rest\View(serializerGroups={"usuario"})
     */
    public function getActions(UsuarioBDRepository $uBD){
        return $uBD->findAll();
    }

    /**
     * @Rest\Get(path="/usuarios/list")
     * @Rest\View(serializerGroups={"usuario"})
     */
    public function getUsers(UsuarioBDRepository $uBD){
        return $uBD->findAll();
    }
}
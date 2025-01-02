<?php
require_once '../models/Service.php';
require_once '../models/User.php';
require_once '../models/Local.php';

class AdminController {
    private $serviceModel;
    private $userModel;
    private $localModel;

    public function __construct() {
        $this->serviceModel = new Service();
        $this->userModel = new User();
        $this->localModel = new Local();
    }

    public function listarServicos() {
        return $this->serviceModel->listarServicos();
    }

    public function listarUtilizadores() {
        return $this->userModel->listarUtilizadores();
    }

    public function listarLocais() {
        return $this->localModel->listarLocais();
    }

    public function adicionarServico($nome, $preco, $idLocal) {
        return $this->serviceModel->adicionarServico($nome, $preco, $idLocal);
    }

    public function removerServico($idServico) {
        return $this->serviceModel->removerServico($idServico);
    }
}

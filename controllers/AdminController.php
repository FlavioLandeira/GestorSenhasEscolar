<?php
require_once '../models/Service.php';
require_once '../models/User.php';
require_once '../models/Location.php';

class AdminController {
    private $serviceModel;
    private $userModel;
    private $locationModel;

    public function __construct() {
        $this->serviceModel = new Service();
        $this->userModel = new User();
        $this->locationModel = new Location();
    }

    public function adicionarServico($name, $price, $localId) {
        return $this->serviceModel->createService($name, $price, $localId);
    }

    public function removerServico($serviceId) {
        return $this->serviceModel->deleteService($serviceId);
    }

    public function visualizarEstatisticas() {
        return $this->serviceModel->getStatistics();
    }
}

?>
<?php
require_once '../models/Queue.php';

class ClienteController {
    private $queueModel;

    public function __construct() {
        $this->queueModel = new Queue();
    }

    public function retirarSenha($userId, $serviceId) {
        return $this->queueModel->addToQueue($userId, $serviceId);
    }

    public function acompanharFila($serviceId) {
        return $this->queueModel->getQueueStatus($serviceId);
    }
}

?>
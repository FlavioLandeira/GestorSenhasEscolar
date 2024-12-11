<?php
require_once '../models/Queue.php';

class FuncionarioController {
    private $queueModel;

    public function __construct() {
        $this->queueModel = new Queue();
    }

    public function chamarProximo($serviceId) {
        return $this->queueModel->processNextInQueue($serviceId);
    }

    public function visualizarHistorico($serviceId) {
        return $this->queueModel->getServiceHistory($serviceId);
    }
}
?>
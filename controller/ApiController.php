<?php
require_once __DIR__ . '/../modules/ApiClient.php';

class ApiController {
    private $apiClient;

    public function __construct() {
        $this->apiClient = new ApiClient();
    }

    public function consultarRuc($ruc) {
        return $this->apiClient->fetch('sunat/ruc', $ruc);
    }

    public function consultarDni($dni) {
        return $this->apiClient->fetch('reniec/dni', $dni);
    }
}

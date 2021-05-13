<?php

namespace WebserviceCaixa;

use WebserviceCaixa\Models\Beneficiario;
use WebserviceCaixa\Models\Titulo;

class Webservice
{
    /**
     * @var \WebserviceCaixa\Models\Beneficiario
     */
    public $beneficiario;

    public function __construct(Beneficiario $beneficiario)
    {
        $this->beneficiario = $beneficiario;
    }

    public function incluiBoleto(Titulo $titulo)
    {
    }
}

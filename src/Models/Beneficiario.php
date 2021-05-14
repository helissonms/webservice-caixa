<?php

namespace WebserviceCaixa\Models;

class Beneficiario
{
    /**
     * @property string
     */
    protected $cnpj;

    /**
     * @property string
     */
    protected $codigo;

    /**
     * @param string $cnpj
     * @param string $codigo Convênio sem o dígito.
     */
    public function __construct(string $cnpj, string $codigo)
    {
        $this->cnpj = $cnpj;
        $this->codigo = $codigo;
    }
}

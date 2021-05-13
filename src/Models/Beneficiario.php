<?php

namespace WebserviceCaixa\Models;

class Beneficiario
{
    protected string $cnpj;

    protected string $codigo;

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

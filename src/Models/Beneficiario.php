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
        $this->cnpj = preg_replace('/\D/', '', $cnpj);
        $this->codigo = $codigo;
    }

    /**
     * @return string
     */
    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }
}

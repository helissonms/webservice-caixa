<?php

namespace WebserviceCaixa\Models;

class SacadorAvalista extends Pessoa
{
    /**
     * Definição do Sacador / Avalista do título
     *
     * @param string $tipo Tipo do sacador/avalista (PF ou PJ) para saber qual documento estará sendo validado e enviado
     * @param string $nome (N016/N021) Nome ou Razão social do sacador/avalista, dependendo do tipo (PF ou PJ)
     * @param string $documento (N016/N021) CPF ou CNPJ do pagado, dependendo do tipo (PF ou PJ)
     *
     * @return void
     */
    public function __construct(string $tipo, string $nome, string $documento)
    {
        parent::__construct($tipo, $nome, $documento);
    }
}

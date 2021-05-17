<?php

namespace WebserviceCaixa\Models;

use DOMElement;
use DOMNode;

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

    public function toDOMNode(DOMNode $no)
    {
        $sacadorAvalista = $no->appendChild(new DOMElement('SACADOR_AVALISTA'));

        switch ($this->tipo) {
            case self::TIPO_PESSOA_JURIDICA:
                $sacadorAvalista->appendChild(new DOMElement('CNPJ', $this->documento));
                $sacadorAvalista->appendChild(new DOMElement('RAZAO_SOCIAL', $this->nome));
                break;
            default:
                $sacadorAvalista->appendChild(new DOMElement('CPF', $this->documento));
                $sacadorAvalista->appendChild(new DOMElement('NOME', $this->nome));
                break;
        }

        return $no;
    }
}

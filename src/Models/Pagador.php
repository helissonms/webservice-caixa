<?php

namespace WebserviceCaixa\Models;

use DOMElement;
use DOMNode;

class Pagador extends Pessoa
{
    /**
     * (N017) Se a ação pós vencimento for PROTESTAR, é necessário informar o endereço do pagador
     *
     * @property \WebserviceCaixa\Models\Endereco
     */
    protected $endereco;

    /**
     * Definição do pagador do título
     *
     * @param string $tipo Tipo do pagador (PF ou PJ) para saber qual documento estará sendo validado e enviado
     * @param string $nome (N016/N021) Nome ou Razão social do pagador, dependendo do tipo (PF ou PJ)
     * @param string $documento (N016/N021) CPF ou CNPJ do pagado, dependendo do tipo (PF ou PJ)
     * @param \WebserviceCaixa\Models\Endereco $endereco (N017) Se a ação pós vencimento for PROTESTAR, é necessário informar o endereço do pagador
     *
     * @return void
     */
    public function __construct(string $tipo, string $nome, string $documento, Endereco $endereco = null)
    {
        parent::__construct($tipo, $nome, $documento);

        $this->endereco = $endereco;
    }

    /**
     * @return string
     */
    public function getEndereco()
    {
        return $this->endereco;
    }

    public function toDOMNode(DOMNode $no)
    {
        $pagador = $no->appendChild(new DOMElement('PAGADOR'));

        switch ($this->tipo) {
            case self::TIPO_PESSOA_JURIDICA:
                $pagador->appendChild(new DOMElement('CNPJ', $this->documento));
                $pagador->appendChild(new DOMElement('RAZAO_SOCIAL', $this->nome));
                break;
            default:
                $pagador->appendChild(new DOMElement('CPF', $this->documento));
                $pagador->appendChild(new DOMElement('NOME', $this->nome));
                break;
        }

        if ($this->endereco) {
            $this->endereco->toDOMNode($pagador);
        }

        return $no;
    }
}

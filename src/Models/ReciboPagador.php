<?php

namespace WebserviceCaixa\Models;

use DOMElement;
use DOMNode;

class ReciboPagador
{
    protected $mensagens = [];

    public function __construct(array $mensagens = [])
    {
        $this->mensagens = $mensagens;
    }

    public function toDOMNode(DOMNode $no)
    {
        if (empty($this->mensagens)) {
            return $no;
        }

        $reciboPagador = $no->appendChild(new DOMElement('RECIBO_PAGADOR'));

        $mensagens = $reciboPagador->appendChild(new DOMElement('MENSAGENS'));

        foreach ($this->mensagens as $mensagem) {
            $mensagens->appendChild(new DOMElement('MENSAGEM', $mensagem));
        }

        return $no;
    }
}

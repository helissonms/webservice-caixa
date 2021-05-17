<?php

namespace WebserviceCaixa\Models;

use DOMElement;
use DOMNode;

class FichaCompensacao
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

        $fichaCompensacao = $no->appendChild(new DOMElement('FICHA_COMPENSACAO'));

        $mensagens = $fichaCompensacao->appendChild(new DOMElement('MENSAGENS'));

        foreach ($this->mensagens as $mensagem) {
            $mensagens->appendChild(new DOMElement('MENSAGEM', $mensagem));
        }

        return $no;
    }
}

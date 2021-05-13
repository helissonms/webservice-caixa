<?php

namespace WebserviceCaixa\Exceptions;

use Exception;

class PosVencimentoAcaoInvalidaException extends Exception
{
    public function __construct(string $acao)
    {
        parent::__construct("A ação pós vencimento, {$acao}, não é uma ação válida!");
    }
}

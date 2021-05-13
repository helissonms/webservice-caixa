<?php

namespace WebserviceCaixa\Exceptions;

use Exception;

class JurosTipoInvalidoException extends Exception
{
    public function __construct(string $tipo)
    {
        parent::__construct("O tipo {$tipo}, não é um tipo de juros válido!");
    }
}

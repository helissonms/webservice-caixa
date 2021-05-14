<?php

namespace WebserviceCaixa\Models;

use WebserviceCaixa\Exceptions\PosVencimentoAcaoInvalidaException;

class PosVencimento
{
    public const ACAO_DEVOLVER = 'DEVOLVER';
    public const ACAO_PROTESTAR = 'PROTESTAR';

    public static $acoes = [
        self::ACAO_DEVOLVER,
        self::ACAO_PROTESTAR,
    ];

    /**
     * (NE013) Código de Instrução de Protesto ou Devolução.
     *
     * @property string
     */
    protected $acao;

    /**
     * (NE014) Número de dias para o protesto ou baixa por devolução do título não pago após o vencimento.
     *
     * @property int
     */
    protected $dias;

    /**
     * (NE013 e NE014) Instrução de Protesto ou Devolução
     *
     * @param string $acao (NE013) Código de Instrução de Protesto ou Devolução.
     * @param int $dias (NE014) Número de dias para o protesto ou baixa por devolução do título não pago após o vencimento.
     *
     * @return void
     */
    public function __construct(string $acao = null, int $dias = null)
    {
        $this->acao = $acao ?: self::ACAO_DEVOLVER;

        if (! in_array($this->acao, static::$acoes)) {
            throw new PosVencimentoAcaoInvalidaException($this->acao);
        }

        if (is_null($dias)) {
            $this->dias = $this->acao === self::ACAO_DEVOLVER ? 0 : 2;
        } else {
            $this->dias = $dias;
        }
    }

    /**
     * @return string
     */
    public function getAcao()
    {
        return $this->acao;
    }

    /**
     * @return int
     */
    public function getDias()
    {
        return $this->dias;
    }
}

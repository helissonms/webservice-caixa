<?php

namespace WebserviceCaixa\Models;

use DOMElement;
use DOMNode;
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
    protected $numeroDias;

    /**
     * (NE013 e NE014) Instrução de Protesto ou Devolução
     *
     * @param string $acao (NE013) Código de Instrução de Protesto ou Devolução.
     * @param int $numeroDias (NE014) Número de dias para o protesto ou baixa por devolução do título não pago após o vencimento.
     *
     * @return void
     */
    public function __construct(string $acao = null, int $numeroDias = null)
    {
        $this->acao = $acao ?: self::ACAO_DEVOLVER;

        if (! in_array($this->acao, static::$acoes)) {
            throw new PosVencimentoAcaoInvalidaException($this->acao);
        }

        if (is_null($numeroDias)) {
            $this->numeroDias = $this->acao === self::ACAO_DEVOLVER ? 0 : 2;
        } else {
            $this->numeroDias = $numeroDias;
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
    public function getNumeroDias()
    {
        return $this->numeroDias;
    }

    public static function devolver(int $numeroDias = 0)
    {
        return new static(self::ACAO_DEVOLVER, $numeroDias);
    }

    public static function protestar(int $numeroDias = 2)
    {
        return new static(self::ACAO_PROTESTAR, $numeroDias);
    }

    public function toDOMNode(DOMNode $no)
    {
        $posVencimento = $no->appendChild(new DOMElement('POS_VENCIMENTO'));

        $posVencimento->appendChild(new DOMElement('ACAO', $this->acao));
        $posVencimento->appendChild(new DOMElement('NUMERO_DIAS', $this->numeroDias));

        return $no;
    }
}

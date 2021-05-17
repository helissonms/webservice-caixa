<?php

namespace WebserviceCaixa\Models;

use DOMElement;
use DOMNode;
use WebserviceCaixa\Exceptions\JurosTipoInvalidoException;

class Juros
{
    public const TIPO_ISENTO = 'ISENTO';
    public const TIPO_VALOR_POR_DIA = 'VALOR_POR_DIA';
    public const TIPO_TAXA_MENSAL = 'TAXA_MENSAL';

    public static $tipos = [
        self::TIPO_ISENTO,
        self::TIPO_VALOR_POR_DIA,
        self::TIPO_TAXA_MENSAL,
    ];

    /**
     * (NE009) Tipo do juros mora
     *
     * @property string
     */
    protected $tipo;

    /**
     * (NE010) Data de inicio da cobrança do juros. Deve ser maior que a data de vencimento do título
     *
     * @property DateTimeInterface
     */
    protected $data;

    /**
     * (NE011) Define o valor fixo a ser cobrado. Caso o tipo seja ISENTO, o valor sempre será 0.00.
     *
     * @property float
     */
    protected $valor;

    /**
     * (NE011) Define o percentual do valor original a ser cobrado
     *
     * @property float
     */
    protected $percentual;

    /**
     * Juros Mora do título
     * Se o tipo for ISENTO, o valor será 0.00
     *
     * @param string $tipo (NE009) Tipo do juros mora
     *
     * @throws \WebserviceCaixa\Exceptions\JurosTipoInvalidoException
     */
    public function __construct(string $tipo)
    {
        if (! in_array($tipo, static::$tipos)) {
            throw new JurosTipoInvalidoException($tipo);
        }

        $this->tipo = $tipo;
    }

    /**
     * (NE009) Tipo do juros mora
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * (NE010) Data de inicio da cobrança do juros.
     * Deve ser maior que a data de vencimento do título
     *
     * @param DateTimeInterface $data
     *
     * @return \WebserviceCaixa\Models\Juros
     */
    public function setData(DateTimeInterface $data = null)
    {
        if ($this->tipo === self::TIPO_ISENTO) {
            $this->data = null;

            return $this;
        }

        $this->data = $data;
        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * (NE011) Define o valor fixo a ser cobrado.
     * Caso o tipo seja ISENTO, o valor sempre será 0.00.
     *
     * @param float $valor
     *
     * @return \WebserviceCaixa\Models\Juros
     */
    public function setValor(float $valor = null)
    {
        if ($this->tipo === self::TIPO_ISENTO) {
            $this->valor = 0.00;

            return $this;
        }

        $this->valor = $valor;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * (NE011) Define o percentual do valor original a ser cobrado
     *
     * @param float $percentual
     *
     * @return \WebserviceCaixa\Models\Juros
     */
    public function setPercentual(float $percentual = null)
    {
        if ($this->tipo === self::TIPO_ISENTO) {
            $this->percentual = null;
        }

        $this->percentual = $percentual;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPercentual()
    {
        return $this->percentual;
    }

    /**
     * Cria uma instancia do juros do tipo ISENTO
     *
     * @return \WebserviceCaixa\Models\Juros
     */
    public static function isento()
    {
        return (new Juros(self::TIPO_ISENTO))
            ->setValor(0.00);
    }

    /**
     * Cria uma instancia do juros do tipo TAXA_MENSAL
     *
     * @param DateTimeInterface $data (NE010) Data de inicio da cobrança do juros. Deve ser maior que a data de vencimento do título
     * @param float $valor
     * @param float $percentual
     *
     * @return \WebserviceCaixa\Models\Juros
     */
    public static function taxaMensal(DateTimeInterface $data, float $valor = 0.0, float $percentual = 0.0)
    {
        return (new Juros(self::TIPO_TAXA_MENSAL))
            ->setData($data)
            ->setValor($valor)
            ->setPercentual($percentual);
    }

    /**
     * Cria uma instancia do juros do tipo VALOR_POR_DIA
     *
     * @param DateTimeInterface $data (NE010) Data de inicio da cobrança do juros. Deve ser maior que a data de vencimento do título
     * @param float $valor
     * @param float $percentual
     *
     * @return \WebserviceCaixa\Models\Juros
     */
    public static function valorPorDia(DateTimeInterface $data, float $valor = 0.0, float $percentual = 0.0)
    {
        return (new Juros(self::TIPO_VALOR_POR_DIA))
            ->setData($data)
            ->setValor($valor)
            ->setPercentual($percentual);
    }

    public function toDOMNode(DOMNode $no)
    {
        $juros = $no->appendChild(new DOMElement('JUROS_MORA'));

        $juros->appendChild(new DOMElement('TIPO', $this->tipo));

        switch ($this->tipo) {
            case self::TIPO_ISENTO:
                $juros->appendChild(new DOMElement('VALOR', number_format($this->valor, 2, '.', '')));
                break;
            case self::TIPO_VALOR_POR_DIA:
                $juros->appendChild(new DOMElement('DATA', $this->data->format('Y-m-d')));
                $juros->appendChild(new DOMElement('VALOR', number_format($this->valor, 2, '.', '')));
                break;
            case self::TIPO_TAXA_MENSAL:
                $juros->appendChild(new DOMElement('DATA', $this->data->format('Y-m-d')));
                $juros->appendChild(new DOMElement('PERCENTUAL', number_format($this->percentual, 2, '.', '')));
                break;
        }

        return $no;
    }
}

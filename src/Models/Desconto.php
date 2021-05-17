<?php

namespace WebserviceCaixa\Models;

use DateTimeInterface;
use DOMElement;
use DOMNode;
use Illuminate\Support\Str;

class Desconto
{
    // (NE024A) Estes tipos, só permitem uma configuração de desconto por título
    public const TIPO_ISENTO = 'ISENTO';
    public const TIPO_VALOR_FIXO_ATE_DATA = 'VALOR_FIXO_ATE_DATA';
    public const TIPO_PERCENTUAL_ATE_DATA = 'PERCENTUAL_ATE_DATA';

    // (NE024A) Estes tipos, permitem até 3 configurações de desconto por título
    public const TIPO_VALOR_ANTECIPACAO_DIA_CORRIDO = 'VALOR_ANTECIPACAO_DIA_CORRIDO';
    public const TIPO_VALOR_ANTECIPACAO_DIA_UTIL = 'VALOR_ANTECIPACAO_DIA_UTIL';
    public const TIPO_PERCENTUAL_ANTECIPACAO_DIA_CORRIDO = 'PERCENTUAL_ANTECIPACAO_DIA_CORRIDO';
    public const TIPO_PERCENTUAL_ANTECIPACAO_DIA_UTIL = 'PERCENTUAL_ANTECIPACAO_DIA_UTIL';

    /**
     * (NE024A) Parâmetro para aplicação de desconto(s), com valor/percentual fixo ou com antecipação por dia útil/corrido.
     *
     * @property string
     */
    protected $tipo;

    /**
     * (NE024) Valor fixo do desconto (dependendo do tipo)
     *
     * @property float
     */
    protected $valor;

    /**
     * (NE024) Valor percentual do desconto (dependendo do tipo)
     *
     * @property float
     */
    protected $percentual;

    /**
     * (NE025) Data limite do desconto do título de cobrança
     */
    protected $data;

    /**
     * (NE024A e NE025) Configuração do desconto a ser aplicado. Dependendo do tipo, pode ser aplicados até 3 descontos
     *
     * @param string $tipo (NE024A) Parâmetro para aplicação de desconto(s), com valor/percentual fixo ou com antecipação por dia útil/corrido.
     * @param float $valor (NE024) Valor fixo do desconto (dependendo do tipo)
     * @param float $percentual (NE024) Valor percentual do desconto (dependendo do tipo)
     * @param DateTimeInterface $data (NE025) Data limite do desconto do título de cobrança
     *
     * @return void
     */
    public function __construct(string $tipo, float $valor = 0.00, float $percentual = 0.00, DateTimeInterface $data = null)
    {
        $this->tipo = $tipo;
        $this->valor = $valor;
        $this->percentual = $percentual;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @return float
     */
    public function getPercentual()
    {
        return $this->percentual;
    }

    /**
     * @return null|DateTimeInterface
     */
    public function getData()
    {
        return $this->data;
    }

    public function toDOMNode(DOMNode $no)
    {
        $desconto = $no->appendChild(new DOMElement('DESCONTO'));

        if (in_array($this->tipo, [self::TIPO_VALOR_FIXO_ATE_DATA, self::TIPO_PERCENTUAL_ATE_DATA])) {
            $desconto->appendChild(new DOMElement('DATA', $this->data->format('Y-m-d')));
        }

        if ($this->tipo === self::TIPO_ISENTO || Str::startsWith($this->tipo, 'VALOR')) {
            $desconto->appendChild(new DOMElement('VALOR', number_format($this->valor, 2, '.', '')));
        }

        if (Str::startsWith($this->tipo, 'PERCENTUAL')) {
            $percentual = number_format($this->percentual, 2, '.', '');
        }

        $desconto->appendChild(new DOMElement('TIPO', $this->tipo));

        return $no;
    }
}

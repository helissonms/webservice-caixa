<?php

namespace WebserviceCaixa\Models;

class Pagamento
{
    public const TIPO_ACEITA_QUALQUER_VALOR = 'ACEITA_QUALQUER_VALOR';
    public const TIPO_ACEITA_VALORES_ENTRE_MINIMO_MAXIMO = 'ACEITA_VALORES_ENTRE_MINIMO_MAXIMO';
    public const TIPO_SOMENTE_VALOR_MINIMO = 'SOMENTE_VALOR_MINIMO';
    public const TIPO_NAO_ACEITA_VALOR_DIVERGENTE = 'NAO_ACEITA_VALOR_DIVERGENTE';
    public const TIPO_ACEITA_QUALQUER_VALOR = 'ACEITA_QUALQUER_VALOR';
    public const TIPO_ACEITA_VALORES_ENTRE_MINIMO_MAXIMO = 'ACEITA_VALORES_ENTRE_MINIMO_MAXIMO';

    /**
     * (NE030) Identificação do Tipo de Pagamento
     */
    public static $tipos = [
        self::TIPO_ACEITA_QUALQUER_VALOR,
        self::TIPO_ACEITA_VALORES_ENTRE_MINIMO_MAXIMO,
        self::TIPO_SOMENTE_VALOR_MINIMO,
        self::TIPO_NAO_ACEITA_VALOR_DIVERGENTE,
        self::TIPO_ACEITA_QUALQUER_VALOR,
        self::TIPO_ACEITA_VALORES_ENTRE_MINIMO_MAXIMO,
    ];

    /**
     * (NE030) Identificação do Tipo de Pagamento
     *
     * @property string
     */
    protected $tipo;

    /**
     * (NE031) Quantidade de Pagamento Possíveis
     *
     * @property int
     */
    protected $quantidadePermitida;

    /**
     * (NE032) Identificar o Valor Mínimo admissível para pagamento
     *
     * @property float
     */
    protected $valorMinimo;

    /**
     * (NE032) Identificar o Valor Máximo admissível para pagamento
     *
     * @property float
     */
    protected $valorMaximo;

    /**
     * (NE033) Identificar o Percentual Mínimo admissível para pagamento
     *
     * @property float
     */
    protected $percentualMinimo;

    /**
     * (NE033) Identificar o Percentual Máximo admissível para pagamento
     *
     * @property float
     */
    protected $percentualMaximo;

    /**
     * Configuração
     */
    public function __construct(string $tipo, int $quantidadePermitida, float $valorMinimo = null, float $valorMaximo = null, float $percentualMinimo = null, float $percentualMaximo = null)
    {
        $this->tipo = $tipo;
        $this->quantidadePermitida = $quantidadePermitida;
        $this->valorMinimo = $valorMinimo;
        $this->valorMaximo = $valorMaximo;
        $this->percentualMinimo = $percentualMinimo;
        $this->percentualMaximo = $percentualMaximo;
    }

    /**
     * (NE030) Identificação do Tipo de Pagamento
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * (NE031) Quantidade de Pagamento Possíveis
     *
     * @return int
     */
    public function getQuantidadePermitida()
    {
        return $this->quantidadePermitida;
    }

    /**
     * (NE032) Identificar o Valor Mínimo admissível para pagamento
     *
     * @return null|float
     */
    public function getValorMinimo()
    {
        return $this->valorMinimo;
    }

    /**
     * (NE032) Identificar o Valor Máximo admissível para pagamento
     *
     * @return null|float
     */
    public function getValorMaximo()
    {
        return $this->valorMaximo;
    }

    /**
     * (NE033) Identificar o Percentual Mínimo admissível para pagamento
     *
     * @return null|float
     */
    public function getPercentualMinimo()
    {
        return $this->percentualMinimo;
    }

    /**
     * (NE033) Identificar o Percentual Máximo admissível para pagamento
     *
     * @return null|float
     */
    public function getPercentualMaximo()
    {
        return $this->percentualMaximo;
    }
}

<?php

namespace WebserviceCaixa\Models;

use DateTimeInterface;
use DOMElement;
use DOMNode;

class Multa
{
    /**
     * (NE022) Data a partir da qual a multa deverá ser cobrada.
     *
     * @property null|DateTimeInterface
     */
    protected $data;

    /**
     * (NE023) Valor de multa a ser aplicado sobre o valor do Título
     *
     * @property null|float
     */
    protected $valor;

    /**
     * (NE023) Percentual de multa a ser aplicado sobre o valor do Título
     *
     * @property null|float
     */
    protected $percentual;

    /**
     * (NE022 e NE023) Configuração da multa a ser aplicada.
     * Valor ou Percentual vai ser aplicado. Deixar como 'null' o que não for utilizado.
     * Caso informe o 'valor' e o 'percentual', a prioridade será do 'valor'.
     *
     * @param DateTimeInterface $data (NE022) Data a partir da qual a multa deverá ser cobrada.
     * @param null|float $valor (NE023) Valor de multa a ser aplicado sobre o valor do Título
     * @param null|float $percentual (NE023) Percentual de multa a ser aplicado sobre o valor do Título
     *
     * @return void
     */
    public function __construct(DateTimeInterface $data, float $valor = null, float $percentual = null)
    {
        $this->valor = $valor;
        $this->percentual = $percentual;
        $this->data = $data;
    }

    /**
     * @return DateTimeInterface
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return null|float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @return null|float
     */
    public function getPercentual()
    {
        return $this->percentual;
    }

    public function toDOMNode(DOMNode $no)
    {
        $multa = $no->appendChild(new DOMElement('MULTA'));

        $multa->appendChild(new DOMElement('DATA', $this->data->format('Y-m-d')));

        $valor = number_format(is_null($this->valor) ? $this->percentual : $this->valor, 2, '.', '');
        $propriedade = is_null($this->valor) ? 'PERCENTUAL' : 'VALOR';

        $multa->appendChild(new DOMElement($propriedade, $valor));

        return $no;
    }
}

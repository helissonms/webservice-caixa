<?php

namespace WebserviceCaixa\Models;

use DateTimeInterface;
use DateTime;

class Titulo
{
    /**
     * (NE003) Número do título
     *
     * @property string
     */
    protected $numero;

    /**
     * (NE004) Data de vencimento do título
     *
     * @property DateTimeInterface
     */
    protected $vencimento;

    /**
     * (NE005) Valor original do título
     *
     * @property float
     */
    protected $valor;

    /**
     * (NE006) Espécie do título
     *
     * @property int
     */
    protected $especie;

    /**
     * (NE007) Identificação de título Aceito (S) ou Não Aceito (N)
     *
     * @property string
     */
    protected $aceite;

    /**
     * (NE015) Código adotado pela FEBRABAN para identificar a moeda referenciada no Título.
     *
     * @property string
     */
    protected $codigoMoeda = '09';

    /**
     * (NE012) Valor do abatimento (redução do valor do documento, devido a algum problema)
     *
     * @property float
     */
    protected $valorAbatimento = 0.00;

    /**
     * (NE008) Data de emissão do título
     *
     * @property DateTimeInterface
     */
    protected $emissao;

    /**
     * (NE009 e NE010) Configuração dos Juros
     *
     * @property \WebserviceCaixa\Models\Juros
     */
    protected $juros;

    /**
     * (NE013 e NE014) Instrução de Protesto ou Devolução
     *
     * @property \WebserviceCaixa\Models\PosVencimento
     */
    protected $posVencimento;

    /**
     * (NE016) Configurações do Pagador
     *
     * @property \WebserviceCaixa\Models\Pagador
     */
    protected $pagador;

    /**
     * (NE030, NE031, NE032 e NE033) Configuração do pagamento
     *
     * @property \WebserviceCaixa\Models\Pagamento
     */
    protected $pagamento;

     /**
     * (NE024, NE024A e NE025) Array de descontos, sendo considerado até 3 descontos
     *
     * @property array
     */
    protected $descontos = [];

    /**
     * (NE026) Valor do IOF a Ser Recolhido
     *
     * @property float
     */
    protected $valorIof;

    /**
     * (NE027) Campo destinado para uso da Empresa Beneficiário para identificação do Título. (25 caracteres)
     *
     * @property string
     */
    protected $identificacaoEmpresa;

    /**
     * (NE028) Até duas linhas de 40 caracteres. Cada índice do array representa uma linha.
     *
     * @property array
     */
    protected $fichaCompensacaoMensagens = [];

    /**
     * (NE029) Até duas linhas de 40 caracteres. Cada índice do array representa uma linha.
     *
     * @property array
     */
    protected $reciboPagadorMensagens = [];

    /**
     * Título a ser registrado
     *
     * @param string $numero (NE003) Número do título
     * @param DateTimeInterface $vencimento (NE004) Data de vencimento do título
     * @param float $valor (NE005) Valor original do título
     * @param int $especie (NE006) Espécie do título
     * @param string $aceite (NE007) Identificação de título Aceito (S) ou Não Aceito (N)
     * @param DateTimeInterface $emissao (NE008) Data de emissão do título
     *
     * return @return void
     */
    public function __construct(string $numero, DateTimeInterface $vencimento, float $valor, int $especie = 99, string $aceite = 'S', DateTimeInterface $emissao = new DateTime('now'))
    {
        $this->numero = $numero;
        $this->vencimento = $vencimento;
        $this->valor = $valor;
        $this->especie = $especie;
        $this->aceite = $aceite;

        $this->emissao = $emissao ?: new DateTime('now');
    }

    /**
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * @return DateTimeInterface
     */
    public function getVencimento()
    {
        return $this->vencimento;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @return int
     */
    public function getEspecie()
    {
        return $this->especie;
    }

    /**
     * @return string
     */
    public function getAceite()
    {
        return $this->aceite;
    }

    /**
     * @return DateTimeInterface
     */
    public function getEmissao()
    {
        return $this->emissao;
    }

    /**
     * @param float $valor
     *
     * @return \WebserviceCaixa\Models\Titulo
     */
    public function setValorAbatimento(float $valorAbatimento = 0.0)
    {
        $this->valorAbatimento = $valorAbatimento;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorAbatimento()
    {
        return $this->valorAbatimento;
    }

    /**
     * @param \WebserviceCaixa\Models\Juros $juros
     *
     * @return \WebserviceCaixa\Models\Titulo
     */
    public function setJuros(Juros $juros = null)
    {
        $this->juros = $juros ?: Juros::isento();
        return $this;
    }

    /**
     * @return \WebserviceCaixa\Models\Juros
     */
    public function getJuros()
    {
        return $this->juros ?: Juros::isento();
    }

    /**
     * @param \WebserviceCaixa\Models\PosVencimento $posVencimento
     *
     * @return \WebserviceCaixa\Models\Titulo
     */
    public function setPosVencimento(PosVencimento $posVencimento = null)
    {
        $this->posVencimento = $posVencimento ?: new PosVencimento();
        return $this;
    }

    /**
     * @return \WebserviceCaixa\Models\PosVencimento
     */
    public function getPosVencimento()
    {
        return $this->posVencimento ?: new PosVencimento();
    }

    /**
     * @param \WebserviceCaixa\Models\Pagador $pagador
     *
     * @return \WebserviceCaixa\Models\Titulo
     */
    public function setPagador(Pagador $pagador)
    {
        $this->pagador = $pagador;
        return $this;
    }

    /**
     * @return null|\WebserviceCaixa\Models\Pagador
     */
    public function getPagador()
    {
        return $this->pagador;
    }

    /**
     * @param \WebserviceCaixa\Models\Pagamento $pagamento
     *
     * @return \WebserviceCaixa\Models\Titulo
     */
    public function setPagamento(Pagamento $pagamento)
    {
        $this->pagamento = $pagamento;
        return $this;
    }

    /**
     * @return null|\WebserviceCaixa\Models\Pagamento
     */
    public function getPagamento()
    {
        return $this->pagamento;
    }

    /**
     * (NE024A) Dependendo do tipo do desconto, pode ser adicionado até 3.
     * (NE025) O Desconto 1 é aquele de maior valor e data de aplicação mais distante da Data de Vencimento,
     * enquanto o Desconto 3 é o de menor valor e mais próximo da Data de Vencimento.
     *
     * @param \WebserviceCaixa\Models\Desconto $desconto
     *
     * @return \WebserviceCaixa\Models\Titulo
     */
    public function addDesconto(Desconto $desconto)
    {
        $this->descontos[] = $desconto;
        return $this;
    }

    /**
     * @return array
     */
    public function getDescontos()
    {
        return $this->descontos;
    }

    /**
     * (NE026) Valor do IOF a Ser Recolhido
     *
     * @param float $valor
     *
     * @return \WebserviceCaixa\Models\Titulo
     */
    public function setValorIof(float $valor)
    {
        $this->valorIof = $valor;
        return $this;
    }

    /**
     * @return null|float
     */
    public function getValorIof()
    {
        return $this->valorIof;
    }

    /**
     * (NE027) Campo destinado para uso da Empresa Beneficiário para identificação do Título. (25 caracteres)
     *
     * @param string $identificao
     *
     * @return \WebserviceCaixa\Models\Titulo
     */
    public function setIdentificacaoEmpresa(string $identificao)
    {
        $this->identificacaoEmpresa = $identificao;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getIdentificacaoEmpresa()
    {
        return $this->identificacaoEmpresa;
    }

    /**
     * (NE028) Até duas linhas de 40 caracteres cada. Cada índice do array representa uma linha.
     *
     * @param array $mensagens
     *
     * @return \WebserviceCaixa\Models\Titulo
     */
    public function setFichaCompensacaoMensagens(array $mensagens)
    {
        $this->fichaCompensacaoMensagens = $mensagens;
        return $this;
    }

    /**
     * @return array
     */
    public function getFichaCompensacaoMensagens()
    {
        return $this->fichaCompensacaoMensagens;
    }

    /**
     * (NE029) Até quatro linhas de 40 caracteres cada. Cada índice do array representa uma linha.
     *
     * @param array $mensagens
     *
     * @return \WebserviceCaixa\Models\Titulo
     */
    public function setReciboPagadorMensagens(array $mensagens)
    {
        $this->reciboPagadorMensagens = $mensagens;
        return $this;
    }

    public function getReciboPagadorMensagens()
    {
        return $this->reciboPagadorMensagens;
    }
}

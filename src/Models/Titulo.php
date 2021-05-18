<?php

namespace WebserviceCaixa\Models;

use DateTimeInterface;
use DateTime;
use DOMElement;
use DOMNode;
use Illuminate\Support\Str;

class Titulo
{
    /**
     * (NE002) Nosso Número
     *
     * @property string
     */
    protected $nossoNumero;

    /**
     * (NE003) Número do título
     *
     * @property string
     */
    protected $numeroDocumento;

    /**
     * (NE004) Data de vencimento do título
     *
     * @property DateTimeInterface
     */
    protected $dataVencimento;

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
    protected $tipoEspecie;

    /**
     * (NE007) Identificação de título Aceito (S) ou Não Aceito (N)
     *
     * @property string
     */
    protected $flagAceite = 'N';

    /**
     * (NE008) Data de emissão do título
     *
     * @property DateTimeInterface
     */
    protected $dataEmissao;

    /**
     * (NE009 e NE010) Configuração dos Juros
     *
     * @property \WebserviceCaixa\Models\Juros
     */
    protected $juros;

    /**
     * (NE012) Valor do abatimento (redução do valor do documento, devido a algum problema)
     *
     * @property float
     */
    protected $valorAbatimento = 0.00;

    /**
     * (NE013 e NE014) Instrução de Protesto ou Devolução
     *
     * @property \WebserviceCaixa\Models\PosVencimento
     */
    protected $posVencimento;

    /**
     * (NE015) Código adotado pela FEBRABAN para identificar a moeda referenciada no Título.
     *
     * @property string
     */
    protected $codigoMoeda = '09';

    /**
     * (NE016) Configurações do Pagador
     *
     * @property \WebserviceCaixa\Models\Pagador
     */
    protected $pagador;

    /**
     * (NE016) Configurações do Sacador/Avalista
     *
     * @property \WebserviceCaixa\Models\SacadorAvalista
     */
    protected $sacadorAvalista;

    /**
     * (NE022 e NE023) Configuração da multa a ser aplicada.
     *
     * @property \WebserviceCaixa\Models\Multa
     */
    protected $multa;

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
     * @property \WebserviceCaixa\Models\FichaCompensacao
     */
    protected $fichaCompensacao;

    /**
     * (NE029) Até duas linhas de 40 caracteres. Cada índice do array representa uma linha.
     *
     * @property \WebserviceCaixa\Models\ReciboPagador
     */
    protected $reciboPagador;

    /**
     * (NE030, NE031, NE032 e NE033) Configuração do pagamento
     *
     * @property \WebserviceCaixa\Models\Pagamento
     */
    protected $pagamento;

    /**
     * Título a ser registrado
     *
     * @param string $numeroDocumento (NE003) Número do título
     * @param DateTimeInterface $dataVencimento (NE004) Data de vencimento do título
     * @param float $valor (NE005) Valor original do título
     * @param int $tipoEspecie (NE006) Espécie do título
     * @param string $flagAceite (NE007) Identificação de título Aceito (S) ou Não Aceito (N)
     * @param DateTimeInterface $dataEmissao (NE008) Data de emissão do título
     *
     * return @return void
     */
    public function __construct(string $numeroDocumento, DateTimeInterface $dataVencimento, float $valor, int $tipoEspecie = 99, string $flagAceite = 'N', DateTimeInterface $dataEmissao = null)
    {
        $this->nossoNumero = '14' . str_pad($numeroDocumento, 15, '0', STR_PAD_LEFT);
        $this->numeroDocumento = $numeroDocumento;
        $this->dataVencimento = $dataVencimento;
        $this->valor = $valor;
        $this->tipoEspecie = $tipoEspecie;
        $this->flagAceite = $flagAceite;

        $this->dataEmissao = $dataEmissao ?: new DateTime('now');

        $this->juros = Juros::isento();
        $this->posVencimento = PosVencimento::devolver();
        $this->pagamento = Pagamento::naoAceitaValorDivergente();
    }

    /**
     * @return string
     */
    public function getNossoNumero()
    {
        return $this->nossoNumero;
    }

    /**
     * @return string
     */
    public function getNumeroDocumento()
    {
        return $this->numeroDocumento;
    }

    /**
     * @return DateTimeInterface
     */
    public function getDataVencimento()
    {
        return $this->dataVencimento;
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
    public function getTipoEspecie()
    {
        return $this->especie;
    }

    /**
     * @return string
     */
    public function getCodigoMoeda()
    {
        return $this->codigoMoeda;
    }

    /**
     * @return string
     */
    public function getFlagAceite()
    {
        return $this->flagAceite;
    }

    /**
     * @return DateTimeInterface
     */
    public function getDataEmissao()
    {
        return $this->dataEmissao;
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
        return $this->valorAbatimento ?? 0.00;
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
        return $this->juros;
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
        return $this->posVencimento;
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
     * @param \WebserviceCaixa\Models\SacadorAvalista $sacadorAvalista
     *
     * @return \WebserviceCaixa\Models\Titulo
     */
    public function setSacadorAvalista(SacadorAvalista $sacadorAvalista)
    {
        $this->sacadorAvalista = $sacadorAvalista;
        return $this;
    }

    /**
     * @return null|\WebserviceCaixa\Models\SacadorAvalista
     */
    public function getSacadorAvalista()
    {
        return $this->sacadorAvalista;
    }

    /**
     * @param \WebserviceCaixa\Models\Multa $multa
     *
     * @return \WebserviceCaixa\Models\Titulo
     */
    public function setMulta(Multa $multa)
    {
        $this->multa = $multa;
        return $this;
    }

    /**
     * @return null|\WebserviceCaixa\Models\Multa
     */
    public function getMulta()
    {
        return $this->multa;
    }

    /**
     * @param \WebserviceCaixa\Models\Pagamento $pagamento
     *
     * @return \WebserviceCaixa\Models\Titulo
     */
    public function setPagamento(Pagamento $pagamento = null)
    {
        $this->pagamento = $pagamento ?: Pagamento::naoAceitaValorDivergente();
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
     * @param \WebserviceCaixa\Models\FichaCompensacao $fichaCompensacao
     *
     * @return \WebserviceCaixa\Models\Titulo
     */
    public function setFichaCompensacao(FichaCompensacao $fichaCompensacao = null)
    {
        $this->fichaCompensacao = $fichaCompensacao ?: new FichaCompensacao();
        return $this;
    }

    /**
     * @return null|\WebserviceCaixa\Models\FichaCompensacao
     */
    public function getFichaCompensacao()
    {
        return $this->fichaCompensacao;
    }

    /**
     * (NE029) Até quatro linhas de 40 caracteres cada. Cada índice do array representa uma linha.
     *
     * @param \WebserviceCaixa\Models\ReciboPagador $reciboPagador
     *
     * @return \WebserviceCaixa\Models\Titulo
     */
    public function setReciboPagador(ReciboPagador $reciboPagador)
    {
        $this->reciboPagador = $reciboPagador;
        return $this;
    }

    /**
     * @return null|\WebserviceCaixa\Models\ReciboPagador
     */
    public function getReciboPagador()
    {
        return $this->reciboPagador;
    }

    public function toDOMNode(DOMNode $no)
    {
        $titulo = $no->appendChild(new DOMElement('TITULO'));

        foreach (get_object_vars($this) as $chave => $valor) {
            $propriedade = Str::upper(Str::snake($chave));

            if (is_string($valor) || is_int($valor)) {
                $titulo->appendChild(new DOMElement($propriedade, $valor));

                continue;
            }

            if (is_float($valor)) {
                $titulo->appendChild(new DOMElement($propriedade, number_format($valor, 2, '.', '')));

                continue;
            }

            if (is_array($valor)) {
                if ($chave === 'descontos') {
                    $descontos = $titulo->appendChild(new DOMElement('DESCONTOS'));

                    foreach ($valor ?: [Desconto::isento()] as $desconto) {
                        $desconto->toDOMNode($descontos);
                    }

                    continue;
                }
            }

            if (is_object($valor)) {
                if (method_exists($valor, 'toDOMNode')) {
                    $valor->toDOMNode($titulo);

                    continue;
                }

                if (method_exists($valor, 'format')) {
                    $titulo->appendChild(new DOMElement($propriedade, $valor->format('Y-m-d')));
                }

                continue;
            }
        }

        return $no;
    }
}

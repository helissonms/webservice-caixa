<?php

namespace WebserviceCaixa;

use DateTime;
use DateTimeZone;
use DOMDocument;
use DOMElement;
use DOMNode;
use stdClass;
use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;
use WebserviceCaixa\Models\Beneficiario;
use WebserviceCaixa\Models\Titulo;
use WebserviceCaixa\Models\Juros;
use WebserviceCaixa\Models\PosVencimento;
use WebserviceCaixa\Models\Pagador;
use WebserviceCaixa\Models\Desconto;

class Webservice
{
    public const OPERACAO_ALTERA_BOLETO = 'ALTERA_BOLETO';
    public const OPERACAO_BAIXA_BOLETO = 'BAIXA_BOLETO';
    public const OPERACAO_CONSULTA_BOLETO = 'CONSULTA_BOLETO';
    public const OPERACAO_INCLUI_BOLETO = 'INCLUI_BOLETO';

    /**
     * Unica informação disponível na documentação,
     * é que o endpoint espera receber o valor '2.1'
     *
     * @property string
     */
    protected $versao = '2.1';

    /**
     * (4.1) Usuário do serviço. Não consta mais detalhes na documentação oficial.
     *
     * @property string
     */
    protected $usuarioServico = 'SGCBS02P';

    /**
     * (4.1) Sistema de origem. Não foi dado detalhes na documentação, porém espera receber o valor 'SIGCB'.
     *
     * @property string
     */
    protected $sistemaOrigem = 'SIGCB';

    /**
     * Utilizado especificamente na configuração do parâmetro 'DATA_HORA' do XML.
     *
     * @property DateTimeZone
     */
    protected $timeZone;

    /**
     * @property \WebserviceCaixa\Models\Beneficiario
     */
    public $beneficiario;

    public function __construct(Beneficiario $beneficiario)
    {
        $this->beneficiario = $beneficiario;
    }

    /**
     * @param DateTimeZone $timeZone
     *
     * @return \WebserviceCaixa\Webservice
     */
    public function setTimeZone(DateTimeZone $timeZone)
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    /**
     * Registra o titulo informado como parametro
     *
     * @param \WebserviceCaixa\Models\Titulo $titulo
     *
     * @return stdClass|object
     *
     * @throws \GuzzleHttp\Exception\ClientException
     * @throws \GuzzleHttp\Exception\ServerException
     * @throws \GuzzleHttp\Exception\ConnectException
     * @throws \GuzzleHttp\Exception\TransferException
     */
    public function incluiBoleto(Titulo $titulo)
    {
        [$dom, $dados] = $this->getEstruturaPrincipal($titulo, self::OPERACAO_INCLUI_BOLETO);

        $incluiBoleto = $dados->appendChild(new DOMElement(self::OPERACAO_INCLUI_BOLETO));
        $incluiBoleto->appendChild(new DOMElement('CODIGO_BENEFICIARIO', $this->beneficiario->getCodigo()));

        $incluiBoleto = $titulo->toDOMNode($incluiBoleto);

        $resposta = $this->requisicao(self::OPERACAO_INCLUI_BOLETO, $dom->saveXML());

        return $this->trataResposta((string) $resposta->getBody());
    }

    /**
     * Consulta o titulo informado como parametro.
     * Neste caso só é necessário instanciar o Titulo com parâmetros obrigatórios.
     *
     * @param \WebserviceCaixa\Models\Titulo $titulo
     *
     * @return stdClass|object
     *
     * @throws \GuzzleHttp\Exception\ClientException
     * @throws \GuzzleHttp\Exception\ServerException
     * @throws \GuzzleHttp\Exception\ConnectException
     * @throws \GuzzleHttp\Exception\TransferException
     */
    public function consultaBoleto(Titulo $titulo)
    {
        [$dom, $dados] = $this->getEstruturaPrincipal($titulo, self::OPERACAO_CONSULTA_BOLETO);

        $consultaBoleto = $dados->appendChild(new DOMElement(self::OPERACAO_CONSULTA_BOLETO));
        $consultaBoleto->appendChild(new DOMElement('CODIGO_BENEFICIARIO', $this->beneficiario->getCodigo()));
        $consultaBoleto->appendChild(new DOMElement('NOSSO_NUMERO', $titulo->getNossoNumero()));

        $resposta = $this->requisicao(self::OPERACAO_CONSULTA_BOLETO, $dom->saveXML());

        return $this->trataResposta((string) $resposta->getBody());
    }

    /**
     * @param string $operacao Operação a ser executada
     * @param string $xml XML da operação
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \GuzzleHttp\Exception\ClientException
     * @throws \GuzzleHttp\Exception\ServerException
     * @throws \GuzzleHttp\Exception\ConnectException
     * @throws \GuzzleHttp\Exception\TransferException
     */
    public function requisicao(string $operacao, string $xml)
    {
        $url = $operacao === self::OPERACAO_CONSULTA_BOLETO
            ? 'https://barramento.caixa.gov.br/sibar/ConsultaCobrancaBancaria/Boleto'
            : 'https://barramento.caixa.gov.br/sibar/ManutencaoCobrancaBancaria/Boleto/Externo';

        return (new HttpClient)
            ->post($url, [
                'curl' => [
                    CURLOPT_SSL_CIPHER_LIST => 'DEFAULT:!DH',
                ],
                'headers' => [
                    'Content-Type' => 'application/xml',
                    'SOAPAction' => $operacao,
                ],
                'body' => $xml,
            ]);
    }

    protected function getEstruturaPrincipal(Titulo $titulo, string $operacao = self::OPERACAO_INCLUI_BOLETO)
    {
        $dom = new DOMDocument('1.0', 'utf-8');

        $raiz = $dom->createElementNS('http://schemas.xmlsoap.org/soap/envelope/', 'soapenv:Envelope');
        $raiz->setAttribute('xmlns:sib', 'http://caixa.gov.br/sibar');

        if ($operacao === self::OPERACAO_CONSULTA_BOLETO) {
            $raiz->setAttribute('xmlns:ext', 'http://caixa.gov.br/sibar/consulta_cobranca_bancaria/boleto');
        } else {
            $raiz->setAttribute('xmlns:ext', 'http://caixa.gov.br/sibar/manutencao_cobranca_bancaria/boleto/externo');
        }

        $raiz->appendChild($dom->createElement('soapenv:Header'));

        $corpo = $raiz->appendChild($dom->createElement('soapenv:Body'));

        $servicoEntrada = $corpo->appendChild($dom->createElement('ext:SERVICO_ENTRADA'));

        $cabecalho = $servicoEntrada->appendChild($dom->createElement('sib:HEADER'));

        $this->setDadosCabecalho($cabecalho, $titulo, $operacao);

        $dados = $servicoEntrada->appendChild(new DOMElement('DADOS'));

        $dom->appendChild($raiz);

        return [$dom, $dados];
    }

    protected function setDadosCabecalho(DOMNode $cabecalho, Titulo $titulo, string $operacao = self::OPERACAO_INCLUI_BOLETO)
    {
        $cabecalho->appendChild(new DOMElement('VERSAO', $this->versao));
        $cabecalho->appendChild(new DOMElement('AUTENTICACAO', $this->getAutenticacao($this->beneficiario, $titulo, $operacao)));
        $cabecalho->appendChild(new DOMElement('USUARIO_SERVICO', $this->usuarioServico));
        $cabecalho->appendChild(new DOMElement('OPERACAO', $operacao));
        $cabecalho->appendChild(new DOMElement('SISTEMA_ORIGEM', $this->sistemaOrigem));
        $cabecalho->appendChild(new DOMElement('DATA_HORA', (new DateTime('now', $this->timeZone))->format('YmdHis')));

        return $cabecalho;
    }

    protected function getAutenticacao(Beneficiario $beneficiario, Titulo $titulo, $operacao = self::OPERACAO_INCLUI_BOLETO)
    {
        $dados = $beneficiario->getCodigo();
        $dados .= $titulo->getNossoNumero();

        if (in_array($operacao, [self::OPERACAO_BAIXA_BOLETO, self::OPERACAO_CONSULTA_BOLETO])) {
            $dados .= str_pad('', 8, '0');
            $dados .= str_pad('', 15, '0');
        } else {
            $dados .= $titulo->getDataVencimento()->format('dmY');
            $dados .= str_pad(number_format($titulo->getValor(), 2, '', ''), 15, '0', STR_PAD_LEFT);
        }

        $dados .= $beneficiario->getCnpj();

        $hash = hash('sha256', $dados, true);

        return base64_encode($hash);
    }

    protected function trataResposta(string $xml)
    {
        $dom = new DOMDocument;
        $dom->loadXML($xml);

        return $objeto = $this->converteXMLParaObjeto($dom);
    }

    protected function converteXMLParaObjeto(DOMNode $node, stdClass $objeto = null)
    {
        $objeto = $objeto ?: new stdClass;

        foreach ($node->childNodes as $indice => $node) {
            if ($node->hasAttributes()) {
                $objeto->_attributes = new stdClass;

                foreach ($node->attributes as $attribute) {
                    $objeto->_attributes->{$attribute->name} = $attribute->value;
                }
            }

            $propriedade = property_exists($objeto, $node->localName)
                ? "{$node->localName}{$indice}"
                : $node->localName;

            $objeto->{$propriedade} = $node->hasChildNodes() && $node->firstChild->nodeType === XML_ELEMENT_NODE
                ? $this->converteXMLParaObjeto($node)
                : $node->nodeValue;
        }

        return $objeto;
    }
}

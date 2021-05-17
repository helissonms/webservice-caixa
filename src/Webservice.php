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
        [$dom, $dados] = $this->getEstruturaPrincipal($titulo);

        $incluiBoleto = $dados->appendChild(new DOMElement('INCLUI_BOLETO'));
        $incluiBoleto->appendChild(new DOMElement('CODIGO_BENEFICIARIO', $this->beneficiario->getCodigo()));

        $incluiBoleto = $titulo->toDOMNode($incluiBoleto);

        $resposta = (new HttpClient)
            ->post('https://barramento.caixa.gov.br/sibar/ManutencaoCobrancaBancaria/Boleto/Externo', [
                'curl' => [
                    CURLOPT_SSL_CIPHER_LIST => 'DEFAULT:!DH',
                ],
                'headers' => [
                    'Content-Type' => 'application/xml',
                    'SOAPAction' => 'INCLUI_BOLETO',
                ],
                'body' => $dom->saveXML(),
            ]);

        return $this->trataResposta((string) $resposta->getBody());
    }

    protected function getEstruturaPrincipal(Titulo $titulo, string $operacao = 'INCLUI_BOLETO')
    {
        $dom = new DOMDocument('1.0', 'utf-8');

        $raiz = $dom->createElementNS('http://schemas.xmlsoap.org/soap/envelope/', 'soapenv:Envelope');
        $raiz->setAttribute('xmlns:ext', 'http://caixa.gov.br/sibar/manutencao_cobranca_bancaria/boleto/externo');
        $raiz->setAttribute('xmlns:sib', 'http://caixa.gov.br/sibar');

        $raiz->appendChild($dom->createElement('soapenv:Header'));

        $corpo = $raiz->appendChild($dom->createElement('soapenv:Body'));

        $servicoEntrada = $corpo->appendChild($dom->createElement('ext:SERVICO_ENTRADA'));

        $cabecalho = $servicoEntrada->appendChild($dom->createElement('sib:HEADER'));

        $this->setDadosCabecalho($cabecalho, $titulo);

        $dados = $servicoEntrada->appendChild(new DOMElement('DADOS'));

        $dom->appendChild($raiz);

        return [$dom, $dados];
    }

    protected function setDadosCabecalho(DOMNode $cabecalho, Titulo $titulo, string $operacao = 'INCLUI_BOLETO')
    {
        $cabecalho->appendChild(new DOMElement('VERSAO', $this->versao));
        $cabecalho->appendChild(new DOMElement('AUTENTICACAO', $this->getAutenticacao($this->beneficiario, $titulo)));
        $cabecalho->appendChild(new DOMElement('USUARIO_SERVICO', $this->usuarioServico));
        $cabecalho->appendChild(new DOMElement('OPERACAO', $operacao));
        $cabecalho->appendChild(new DOMElement('SISTEMA_ORIGEM', $this->sistemaOrigem));
        $cabecalho->appendChild(new DOMElement('DATA_HORA', (new DateTime('now', $this->timeZone))->format('YmdHis')));

        return $cabecalho;
    }

    protected function getAutenticacao(Beneficiario $beneficiario, Titulo $titulo)
    {
        $dados = $beneficiario->getCodigo();
        $dados .= $titulo->getNossoNumero();
        $dados .= $titulo->getDataVencimento()->format('dmY');
        $dados .= str_pad(number_format($titulo->getValor(), 2, '', ''), 15, '0', STR_PAD_LEFT);
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

        foreach ($node->childNodes as $node) {
            if ($node->hasAttributes()) {
                $objeto->_attributes = new stdClass;

                foreach ($node->attributes as $attribute) {
                    $objeto->_attributes->{$attribute->name} = $attribute->value;
                }
            }

            $objeto->{$node->localName} = $node->hasChildNodes() && $node->firstChild->nodeType === XML_ELEMENT_NODE
                ? $this->converteXMLParaObjeto($node)
                : $node->nodeValue;
        }

        return $objeto;
    }
}

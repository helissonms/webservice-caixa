<?php

require_once realpath(__DIR__ . '/../vendor/autoload.php');

use WebserviceCaixa\Models\Beneficiario;
use WebserviceCaixa\Models\Desconto;
use WebserviceCaixa\Models\Endereco;
use WebserviceCaixa\Models\FichaCompensacao;
use WebserviceCaixa\Models\Juros;
use WebserviceCaixa\Models\Multa;
use WebserviceCaixa\Models\Pagador;
use WebserviceCaixa\Models\Pagamento;
use WebserviceCaixa\Models\PosVencimento;
use WebserviceCaixa\Models\ReciboPagador;
use WebserviceCaixa\Models\SacadorAvalista;
use WebserviceCaixa\Models\Titulo;
use WebserviceCaixa\Webservice;

$timeZone = new \DateTimeZone('America/Sao_Paulo');

$agora = new \DateTime('now', $timeZone);

$numeroDocumento = '1';
$vencimento = (clone $agora)->add(new \DateInterval('P3D'));
$valor = 5.00;

$pagador = new Pagador(Pagador::TIPO_PESSOA_FISICA, 'Hélisson Müller', '111.111.111-11');
// $pagador = new Pagador(
//     Pagador::TIPO_PESSOA_FISICA,
//     'Hélisson Müller',
//     '111.111.111-11',
//     new Endereco('rua qualquer, 123', 'bairro qualquer', 'GOIANIA', 'GO', '74003010')
// );

$titulo = (new Titulo($numeroDocumento, $vencimento, $valor))
    ->setIdentificacaoEmpresa('MEU_IDENTIFICADOR')
    ->setPagador($pagador)
    // ->setSacadorAvalista(new SacadorAvalista(SacadorAvalista::TIPO_PESSOA_FISICA, 'Hélisson Müller', '111.111.111-11'))
    ->setPagamento(Pagamento::naoAceitaValorDivergente())
    ->setPosVencimento(PosVencimento::devolver())
    // ->setMulta(new Multa($vencimento, null, 1.00))
    ->setJuros(Juros::isento())
    ->addDesconto(Desconto::isento())
    ->setValorIof(0.00)
    ->setFichaCompensacao(new FichaCompensacao([
        'TESTE DE INCLUSAO WEBSERVICE 1',
        'TESTE DE INCLUSAO WEBSERVICE 2',
    ]))
    ->setReciboPagador(new ReciboPagador([
        'TESTE DE INCLUSAO WS MSG PAG 1',
        'TESTE DE INCLUSAO WS MSG PAG 2',
        'TESTE DE INCLUSAO WS MSG PAG 3',
        'TESTE DE INCLUSAO WS MSG PAG 4',
    ]));

$webservice = (new Webservice(new Beneficiario('11.111.111/1111-11', '1234567')))
    ->setTimeZone($timeZone);

$resposta = $webservice->incluiBoleto($titulo);
var_dump($resposta);

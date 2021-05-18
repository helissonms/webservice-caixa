<?php

require_once realpath(__DIR__ . '/../vendor/autoload.php');

use WebserviceCaixa\Models\Beneficiario;
use WebserviceCaixa\Models\Titulo;
use WebserviceCaixa\Webservice;

$timeZone = new \DateTimeZone('America/Sao_Paulo');

$webservice = (new Webservice(new Beneficiario('11.111.111/1111-11', '1234567')))
    ->setTimeZone($timeZone);


$numeroDocumento = '1';
$vencimento = (new \DateTime('now', $timeZone))->add(new \DateInterval('P3D'));
$valor = 5.00;

$resposta = $webservice->consultaBoleto(new Titulo($numeroDocumento, $vencimento, $valor));
var_dump($resposta);

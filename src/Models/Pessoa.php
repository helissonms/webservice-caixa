<?php

namespace WebserviceCaixa\Models;

use WebserviceCaixa\Exceptions\PessoaTipoInvalidoException;
use Illuminate\Support\Str;

class Pessoa
{
    public const TIPO_PESSOA_FISICA = 'PF';
    public const TIPO_PESSOA_JURIDICA = 'PJ';

    public static $tipos = [
        self::TIPO_PESSOA_FISICA,
        self::TIPO_PESSOA_JURIDICA,
    ];

    /**
     * Tipo do pagador (PF ou PJ) para saber qual documento estará sendo validado e enviado
     *
     * @property string
     */
    protected $tipo;

    /**
     * Nome ou Razão social do pagador, dependendo do tipo (PF ou PJ)
     *
     * @property string
     */
    protected $nome;

    /**
     * CPF ou CNPJ do pagador, dependendo do tipo (PF ou PJ)
     *
     * @property string
     */
    protected $documento;

    /**
     * Definição da pessoa (Pagador e/ou Avalista)
     *
     * @param string $tipo Tipo do pagador (PF ou PJ) para saber qual documento estará sendo validado e enviado
     * @param string $nome Nome ou Razão social do pagador, dependendo do tipo (PF ou PJ)
     * @param string $documento CPF ou CNPJ do pagador, dependendo do tipo (PF ou PJ)
     *
     * @return void
     */
    public function __construct(string $tipo, string $nome, string $documento)
    {
        if (! in_array($tipo, self::$tipos)) {
            throw new PessoaTipoInvalidoException($tipo);
        }

        $this->tipo = $tipo;
        $this->nome = Str::upper(Str::ascii($nome));
        $this->documento = preg_replace('/\D/', '', $documento);
    }

     /**
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @return string
     */
    public function getDocumento()
    {
        return $this->documento;
    }
}

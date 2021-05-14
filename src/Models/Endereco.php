<?php

namespace WebserviceCaixa\Models;

class Endereco
{
    /**
     * (NE017) Texto referente a localização da rua / avenida, número e complemento utilizado para entrega de correspondência
     *
     * @property string
     */
    protected $logradouro;

    /**
     * (NE017) Texto referente a localização do bairro utilizado para entrega de correspondência
     *
     * @property string
     */
    protected $bairro;

    /**
     * (NE019) Texto referente ao nome do município componente do endereço utilizado para entrega de correspondência
     *
     * @property string
     */
    protected $cidade;

    /**
     * (NE020) Código do estado, unidade da federação componente do endereço utilizado para entrega de correspondência.
     *
     * @property string
     */
    protected $uf;

    /**
     * (NE018) Código adotado pelos CORREIOS para identificação de logradouros.
     *
     * @property string
     */
    protected $cep;


    /**
     * Endereço do Pagador. Necessário quando a ação pós vencimento do título, for PROTESTAR
     *
     * @param string $logradouro (NE017) Texto referente a localização da rua / avenida, número e complemento utilizado para entrega de correspondência
     * @param string $bairro (NE017) Texto referente a localização do bairro utilizado para entrega de correspondência
     * @param string $cidade (NE019) Texto referente ao nome do município componente do endereço utilizado para entrega de correspondência
     * @param string $uf (NE020) Código do estado, unidade da federação componente do endereço utilizado para entrega de correspondência.
     * @param string $cep (NE018) Código adotado pelos CORREIOS para identificação de logradouros.
     *
     * @return void
     */
    public function __construct(string $logradouro, string $bairro, string $cidade, string $uf, string $cep)
    {
        $this->logradouro = $logradouro;
        $this->bairro = $bairro;
        $this->cidade = $cidade;
        $this->uf = $uf;
        $this->cep = preg_replace('/\D/', '', $cep);
    }

    /**
     * @return string
     */
    public function getLogradouro()
    {
        return $this->logradouro;
    }

    /**
     * @return string
     */
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * @return string
     */
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * @return string
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * @return string
     */
    public function getCep()
    {
        return $this->cep;
    }
}

<?php

namespace App\Http;

class Response{

    /**
     * Código do Status HTTP
     * @var integer
     */
    private $httpCode = 200;


    /**
     * Cabeçalho do Response
     * @var array
     */
    private $headers = [];

    /**
     * Tipo de conteúdo que está sendo retornado
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * Cache time HTTP
     * @var string
     */
    private $cacheControl = 'max-age=31536000';	

    /**
     * Conteúdo do Response
     * @var mixed
     */
    private $content;

    /**
     * Método responsável por iniciar a classe e definir os valores
     * @param integer $httpCode
     * @param mixed $content
     * @param string $contentType
     */
    public function __construct($httpCode,$content,$contentType = 'text/html', $cacheControl = 'max-age=31536000'){
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
        $this->setCacheControl($cacheControl);
    }

    /**
     * Método responsável por altear o content type do response
     * @param string $contentType
     */
    public function setContentType($contentType){
        $this->contentType = $contentType;
        $this->addHeader('Content-Type',$contentType);
    }

    /**
     * Método repository para alterar o cache control do response
     * @param string $cacheControl
     */
    public function setCacheControl($cacheControl){
        $this->cacheControl = $cacheControl;
        $this->addHeader('Cache-Control',$cacheControl);
    }

    /**
     * Método responsável por adicionar um registro no cabeçalho de response
     * @param string $key
     * @param string $value
     */
    public function addHeader($key,$value){
        $this->headers[$key] = $value;
    }

    /**
     * Método responsável por enviar os headers para o navegador
     */
    private function sendHeaders(){
        //STATUS
        http_response_code($this->httpCode);

        //ENVIAR HEADERS
        foreach($this->headers as $key=>$value){
            header($key.': '.$value);
        }
    }

    /**
     * Método responsável por enviar a resposta para o usuário
     */
    public function sendResponse(){
        //ENVIA OS HEADERS
        $this->sendHeaders();

        //IMPRIME O CONTEÚDO
        switch ($this->contentType) {
            case 'text/html':
                echo $this->content;
                exit;
        }
    }
}
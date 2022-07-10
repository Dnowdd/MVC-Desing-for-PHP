<?php

namespace App\Http\Middleware;

use \App\Utils\Cache\File as CacheFile;

class Cache{

    /**
     * Método responsável por verificar se a request atual pode ser cacheada
     * @param Request $request
     * @return boolean
     */
    private function isCacheable($request){
        //VALIDA O TEMPO DE CACHE
        if(getenv('CACHE_TIME') <= 0){
            return false;
        }

        //VALIDA O MÉTODO DA REQUISIÇÃO
        if($request->getHttpMethod() != 'GET'){
            return false;
        }

        //VALIDA O HEADER DE CACHE
        /* $headers = $request->getHeaders();
        if(isset($headers['Cache-Control']) and $headers['Cache-Control'] == 'no-cache'){
            return false;
        } */

        //CACHEÁVEL
        return true;
    }

    /**
     * Método responsável por retornar a hash do cache
     * @param Request $request
     * @return string
     */
    private function getHash($request){
        //URI DA ROTA
        $uri = $request->getRouter()->getUri();

        //QUERY PARAMS
        $queryParams = $request->getQueryParams();
        $uri .= !empty($queryParams) ? '?'.http_build_query($queryParams) : '';

        //REMOVE AS BARRAS E RETORNA A HASH
        return rtrim('route-'.preg_replace('/[^0-9a-zA-Z]/','-',ltrim($uri,'/')),'-');
    }

    /**
     * Método responsável por executar o middleware
     * @param Request $request
     * @param Closure next
     * @return Response
     */
    public function handle($request,$next){
        //VERIFICA SE A REQUEST ATUAL E CACHEÁVEL
        if(!$this->isCacheable($request)) return $next($request);
        
        //HASH DO CACHE
        $hash = $this->getHash($request);

        //RETORNA OS DADOS DO CACHE
        return CacheFile::getCache($hash,getenv('CACHE_TIME'),function() use($request,$next){
            return $next($request);
        });
    }

}
<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;
use \App\Model\Entity\Videos as EntityVideos;

class Home extends Page{
    public static function getPiece($name){
        $itens = '';

        $itens .= View::render('pages/home/'.$name, [
        ]);

        return $itens;
    }

    public static function getPieceWithOrganization($name,$obOrganization){
        $itens = '';

        $itens .= View::render('pages/home/'.$name, [
            'title' => $obOrganization->name
        ]);

        return $itens;
    }

    /**
     * Método responsável por retornar o valor relativo de um Unix Timestamp
     * @param string $ts
     * @return string
     */
    public static function time2str($ts) {
        if(!ctype_digit($ts))
            $ts = strtotime($ts);

            $diff = time() - $ts;
            if($diff == 0)
                return 'now';
            elseif($diff > 0){
                $day_diff = floor($diff / 86400);
                    if($day_diff == 0){
                        if($diff < 60) return 'just now';
                        if($diff < 120) return '1 minute ago';
                        if($diff < 3600) return floor($diff / 60) . ' minutes ago';
                        if($diff < 7200) return '1 hour ago';
                        if($diff < 86400) return floor($diff / 3600) . ' hours ago';
                    }
                    if($day_diff == 1) return 'Yesterday';
                    if($day_diff < 7) return $day_diff . ' days ago';
                    if($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
                    if($day_diff < 60) return 'last month';

                    return date('F Y', $ts);
            }
        else{
            $diff = abs($diff);
            $day_diff = floor($diff / 86400);
            if($day_diff == 0){
                if($diff < 120) return 'in a minute';
                if($diff < 3600) return 'in ' . floor($diff / 60) . ' minutes';
                if($diff < 7200) return 'in an hour';
                if($diff < 86400) return 'in ' . floor($diff / 3600) . ' hours';
            }
            if($day_diff == 1) return 'Tomorrow';
            if($day_diff < 4) return date('l', $ts);
            if($day_diff < 7 + (7 - date('w'))) return 'next week';
            if(ceil($day_diff / 7) < 4) return 'in ' . ceil($day_diff / 7) . ' weeks';
            if(date('n', $ts) == date('n') + 1) return 'next month';

            return date('F Y', $ts);
        }
    }

    /**
     * Método resposável por retornar a caixa de vídeo postado por todos usuários
     * @param string $obUser
     * @return mixed
     */
    public static function getVideoBox(){
        $itens = '';
        $videoCard = '';

        $results = EntityVideos::getVideos(null,'id DESC');

        while($obUser = $results->fetchObject(EntityVideos::class)){
            $time = self::time2str($obUser->date);

            $videoCard .= View::render('pages/home/videoCard',[
                'videoTitle' => $obUser->title,
                'channel' => $obUser->channel,
                'channelUser' => $obUser->channelUser,
                'thumbnail' => $obUser->thumbnail,
                'time' => $time,
                'link' => trim($obUser->video)
            ]);
        } 

        $itens .= View::render('pages/home/videoBox', [
            'videoCard' => $videoCard
        ]);

        return $itens;
    }

    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home
     * @return string
     */
    public static function getHome(){
        //Organização
        $obOrganization = new Organization;

        //VIEW DA HOME
        $content = View::render('pages/home',[
            'hero' => self::getPieceWithOrganization('hero',$obOrganization),
            'banner' => self::getPiece('banner'),
            'videoBox' => self::getVideoBox()
        ]);

        //RETORNA A VIEW DA PÁGINA
        return parent::getPage(
            //NOME DE ARQUIVOS CSS,JS...
            'home',
            //TITLE DA PÁGINA
            $obOrganization->name,
            //DESCRIÇÃO DA PÁGINA
            'Bem-vindos ao RiftMaker.com - Análise as estatísticas de invocadores, melhores campeões, ranking competitivo, times de Clash, Profissionais e muito mais',
            //CONTEUDO DA PÁGINA
            $content
        );
    }
}
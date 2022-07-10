<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class Channel{

    /**
     * ID do usuário
     * @var integer
     */
    public $id;

    /**
     * Username do canal
     * @var string
     */
    public $user;

    /**
     * Nome do canal
     * @var string
     */
    public $name;

    /**
     * Descricao do canal
     * @var string
     */
    public $description;

    /**
     * Email do canal
     * @var string
     */
    public $email;

    /**
     * Senha do canal
     * @var string
     */
    public $password;

    /**
     * Data de criação do canal
     * @var string
     */
    public $date;

    /**
     * Localização do usuário
     * @var string
     */
    public $location;

    /**
     * Link para o spotify do usuário
     * @var string
     */
    public $spotify;

    /**
     * Link para o soundcloud do usuário
     * @var string
     */
    public $soundcloud;

    /**
     * Link para o instagram do usuário
     * @var string
     */
    public $instagram;

    /**
     * Link para o facebook do usuário
     * @var string
     */
    public $facebook;

    /**
     * Link para o discord do usuário
     * @var string
     */
    public $discord;

    /**
     * Método responsável por cadastrar a instancia atual no banco de dados
     * @return boolean
     */
    public function cadastrar(){
        //INSERE A INSTANCIA NO BANCO
        $this->id = (new Database('channel'))->insert([
            'user' => $this->user,
            'name' => $this->name,
            'description' => $this->description,
            'email' => $this->email,
            'password' => $this->password,
            'date' => $this->date,
            'location' => '',
            'spotify' => '',
            'soundcloud' => '',
            'instagram' => '',
            'facebook' => '',
            'discord' => ''
        ]);

        //SUCESSO
        return true;
    }

    /**
     * Método responsável por atualizar os dados do banco
     * @return boolean
     */
    public function atualizar(){
        return (new Database('channel'))->update('id = '.$this->id,[
            'user' => $this->user,
            'name' => $this->name,
            'description' => $this->description,
            'email' => $this->email,
            'password' => $this->password,
            'date' => $this->date,
            'location' => $this->location,
            'spotify' => $this->spotify,
            'soundcloud' => $this->soundcloud,
            'instagram' => $this->instagram,
            'facebook' => $this->facebook,
            'discord' => $this->discord
        ]);

        return true;
    }

    /**
     * Método responsável por excluir um usuário do banco
     * @return boolean
     */
    public function excluir(){
        return (new Database('channel'))->delete('id = '.$this->id);
    }

    /**
     * Método responsável por retornar uma instancia com base em seu id
     * @param integer $id
     * @return User
     */
    public static function getChannelById($id){
        return self::getChannels('id = "'.$id.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar uma instancia com base em seu id
     * @param integer $id
     * @return User
     */
    public static function getChannelByEmail($id){
        return self::getChannels('email = "'.$id.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar uma instancia com base em seu id
     * @param integer $id
     * @return User
     */
    public static function getChannelByUser($id){
        return self::getChannels('user = "'.$id.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar Usuarios
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public static function getChannels($where = null, $order = null, $limit = null, $fields = '*'){
        return (new Database('channel'))->select($where,$order,$limit,$fields);
    }

    /**
     * Método responsável por retornar Usuarios
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public static function getFollows($where = null, $order = null, $limit = null, $fields = '*'){
        return (new Database('follows'))->select($where,$order,$limit,$fields);
    }
}
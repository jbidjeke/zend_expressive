<?php
namespace App\Model;
class PdoConfig extends \PDO {
    
    private $user;
    private $pass;
    private $domain;

    public function __construct($dns, $user, $pass){
        $this->user = $user;
        $this->pass = $pass;
        try {
            parent::__construct( $dns, $this->user, $this->pass );
        } catch (\PDOException $e) {
            $message = 'Erreur : ' . $e->getMessage();
            die($message);
        }
        
    }
}

<?php

class Chat {
 
    // database connection and table name
    private $conn;
 
    // object properties
    public $chat_id;
    public $saison_id;
    public $joueur_id;
    public $joueur_nom;
    public $chat_message;
    public $chat_ts;
    public $chat_type;

 
    public function __construct($db){
        $this->conn = $db;
    }

    function litChatMessages()
    {
      $query = "SELECT webchat_id, saison_id, joueur.joueur_id, joueur.nom nom, message, ts, type
                FROM webchat, joueur
                WHERE saison_id =$this->saison_id
                AND webchat.joueur_id = joueur.joueur_id
                AND type = 'M'
                ORDER BY webchat_id DESC";
      $stmt = $this->conn->prepare( $query );
      $stmt->execute();
      return $stmt;
    }


}
?>
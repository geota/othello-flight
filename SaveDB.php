<?php

// simple wrapper on SQLite3 DB
class SaveDB extends SQLite3  {
  public function __construct($file) {
    $this->open($file);
  }

  // get ID from username string
  public function getId($username) {
    $safe  = $this->escapeString($username);
    $query = "SELECT id FROM games WHERE username='" . $safe . "'";

    return $this->querySingle($query);
  }

  // load the board from game ID
  public function load($id) {
    if(!isset($id)) {
      return false;
    }
    
    $safe   = $this->escapeString($id);
    $query  = "SELECT board FROM games WHERE id=" . $safe;
    $result = $this->querySingle($query);
    $board  = unserialize(base64_decode($result));

    return (gettype($board) == "object") ? $board : false;
  }

  // delete all games
  public function clear() {
    $delete = "DELETE FROM games";

    return $this->exec($delete);
  }

  // insert a new game
  public function insert($username, $board) {
    $id = $this->getId($username);
    if(isset($id)) {
      $this->delete($id);
    }
      
    $safe    = $this->escapeString($username);
    $encoded = base64_encode(serialize($board));
    $query   = "INSERT INTO 'games' (board, username) VALUES ('" . $encoded . "', '" . $safe . "')";
    $result  = $this->exec($query);
    
    return ($result) ? $this->getId($username) : false;
  }

  // delete an existing game by id
  public function delete($id) {
    $safe   = $this->escapeString($id);
    $delete = "DELETE FROM games WHERE id=" . $safe;

    return $this->exec($delete);
  }

  // save board by game ID
  public function save($id, $board) {
    $safe   = $this->escapeString($id);
    $update = "UPDATE games SET board='" . base64_encode(serialize($board)) . "' WHERE id=" . $safe;
    $result = $this->exec($update);
    
    return $result;
  }

}
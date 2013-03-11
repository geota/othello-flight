<?php

class OthelloBoard {
  private $cells;
  private $round;
  private $turn;
  private $score;
  private $cardinals;


  public function __construct($rows = 8, $cols = 8) {
    // make sure the board is a valid size
    if(!isset($rows) || !isset($cols) || $rows < 2 || $cols < 2) {
      $rows = 8;
      $cols = 8;
    }

    //initiate the board to starting state
    $this->cells = array(array());
    for($i = 0; $i < $rows; $i++) {
      for($j = 0; $j < $cols; $j++) {
        $this->cells[$i][$j] = 0;
      }
    }

    // set the default moves
    $middleRow = $rows / 2;
    $middleCol = $cols / 2;
    $this->cells[$middleRow - 1][$middleCol - 1] = 1;
    $this->cells[$middleRow - 1][$middleCol]     = -1;
    $this->cells[$middleRow][$middleCol - 1]     = -1;
    $this->cells[$middleRow][$middleCol]         = 1;

    //Cardinal directions
    $this->cardinals = array(
      array(0, 1),
      array(-1, -1),
      array(-1, 0),
      array(-1, 1),
      array(0, -1),
      array(1, 1),
      array(1, 0),
      array(1, -1));

    //black = -1, white = 1, free = 0
    $this->turn = -1;
    //start at round 4 to account for starting tokens
    $this->round = 4;
    $this->score = 0;
  }

  // build up the table output
  public function draw() {
    //blank space for formatting
    $board  = "<tr><td>&nbsp;</td>";
    $letter = 'a';
    // build up the column header
    foreach($this->cells as $row) {
      $board .= '<th>' . strtoupper($letter++) . '</th>';
    }
    $board .= '</tr><tbody>';

    //build up the board
    foreach($this->cells as $x=>$row) {
      $board .= "<tr><th>" . ($x + 1) . "</th>";
      foreach($row as $y=>$cell) {
        // cell is free, set id to coords format: id="x:y"
        if($cell == 0) {
          $board .= '<td><a class="free" id=' . $x . ":" . $y .'><img src="./assets/img/board.png" width="50" height="50"></td>';
        }
        else if($cell == -1) {
          //cell is black
          $board .= '<td><img src="./assets/img/black.png" width="50" height="50"></td>';
        }
        else {
          //cell is white
          $board .= '<td><img src="./assets/img/white.png" width="50" height="50"></td>';
        }
      }
    }
    $board .= '</tbody></tr>';
    return $board;
  }

  private function isFree($cell) {
    return ($cell == 0);
  }

  private function isEnemy($cell) {
    if($this->isFree($cell)) {
      return false;
    }
    return ($this->turn == $cell) ? false : true;
  }

  public function getScore($color = null) {
    if(!isset($color)) {
      $color = $this->getTurn();
    }

    if($color == "black") {
      return floor(($this->round / 2)) - $this->score;
    }
    else {
      return floor(($this->round / 2)) + $this->score;
    }  
  }


  private function getCell($x, $y) {
    if($x >= count($this->cells)  || $y >= count($this->cells[0])) {
      return null;
    }

    return isset($this->cells[$x][$y]) ? $this->cells[$x][$y] : null;
  }

  public function getTurn() {
    return ($this->turn > 0) ? "white" : "black";
  }

  private function swapTurns() {
    $this->round++;
    $this->turn = ($this->turn < 0) ? 1 : -1;
  }

  //check to see if the current color has any moves left
  public function hasMove() {
    foreach($this->cells as $x=>$row) {
      foreach($row as $y=>$cell) {
        if($cell == 0) {
          if($this->checkCells($x, $y)) {
            return true;
          }
        }
      }
    }
    return false;
  }

  private function validMove($x, $y) {

    $cell = isset($this->cells[$x][$y]) ? $this->cells[$x][$y] : null;
    // if cell is not free... return false
    if(!isset($cell) || $cell != 0) {
      return false;
    }

    return $this->checkCells($x, $y);
  }

  public function placeMove($x, $y) {
    $done = false;

    if($this->validMove($x, $y)) {
      $this->cells[$x][$y] = $this->turn;
      $this->flipCells($x, $y);

      // current player out of moves
      if(!$this->hasMove()) {
        $done = true;
      }

      $this->swapTurns();

      // if new player is also out of moves..
      if(!$this->hasMove()) {

        //both out of moves, we have a winner
        if($done) {
          $winner = ($this->score > 0) ? "white" : "black";
          if($this->score == 0) {
            $winner = "tie";
          }
          return array(
            'winner' => $winner,
            'score' => $this->getScore($winner));
        }
        else {
          // this player needs to skip his move
          $this->swapTurns();
        }
      }

      return true;
    }
    return false;
  }

  private function checkCells($x, $y) {
    // checks in each cardinal direction if it is a valid move
    foreach($this->cardinals as $direction) {
      if($this->check($x, $y, $direction[0], $direction[1])) {
        return true;
      }
    }
    return false;
  }

  private function check($x, $y, $xOffset, $yOffset) {
    $_x = $x + $xOffset;
    $_y = $y + $yOffset;
    $cell = $this->getCell($_x, $_y);

    // the first cell needs to be an enemy
    if(!isset($cell) || !$this->isEnemy($cell)) {
      return false;
    }

    $_x += $xOffset;
    $_y += $yOffset;
    $cell = $this->getCell($_x, $_y);

    // loop until we reach edge => invalid move 
    // or we reach a free cell => invalid move
    // or own own cell => valid move
    while(isset($cell)) {

      if($this->isFree($cell)) {
        return false;
      }

      if(!$this->isEnemy($cell)) {
        return true;
      }

      // keep incrementing by offsets
      $_x += $xOffset;
      $_y += $yOffset;
      $cell = $this->getCell($_x, $_y);
    }

    return false;
  }

  private function flipCells($x, $y) {
    foreach($this->cardinals as $direction) {
      if($this->check($x, $y, $direction[0], $direction[1])) {
        $this->flip($x, $y, $direction[0], $direction[1]);
      }
    }
  }

  private function flip($x, $y, $xOffset, $yOffset) {
    $_x = $x + $xOffset;
    $_y = $y + $yOffset;
    $cell = $this->getCell($_x, $_y);

    // flip until we reach our own color
    while(isset($cell)) {
      if($cell == $this->turn) {
        return true;
      }
      else {
        $this->cells[$_x][$_y] = $this->turn;
        $this->score += $this->turn;
        $_x += $xOffset;
        $_y += $yOffset;
        $cell = $this->getCell($_x, $_y);
      }
    }

    return false;
  }
}
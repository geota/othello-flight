<?php
error_reporting(E_ALL);
ini_set('display_errors', True);
require_once("OthelloBoard.php");
require 'flight/Flight.php';
Flight::register('db', 'SaveDB', array('./data/othello.db'));

// Landing site - loads saved games by user name
Flight::route('/', function() {
  $request = Flight::request();

  // If not a GET request with username display home.php
  if(isset($request->query['username'])) { 
    // grab the saved board
    $user  = $request->query['username'];
    $id    = Flight::db()->getId($user);
    $board = Flight::db()->load($id);

    // if no board saved, create a new one
    if(!isset($board) || !$board) {
      $board = new OthelloBoard();
      // save it in our db
      $id    = Flight::db()->insert($user, $board);
    }

    // render the game page
    return Flight::render('main-wrapper', 
      array(
        'content'  => 'play.php',
        'id'       => $id,
        'board'    => $board,
        'welcome'  => true,
        'username' => $user));
  }
  else {
    // no username supplied, render home.php
   return Flight::render('main-wrapper', array('content' => 'home.php'));
  }
});

// Route that handles the game play functionality
Flight::route('POST /play', function() {
  $request = Flight::request();

  // make sure all params posted correctly
  if(isset($request->data['id'])
      && isset($request->data['username'])
      && isset($request->data['x'])
      && isset($request->data['y'])) {

    // load the board from the game ID
    $board = Flight::db()->load($request->data['id']);

    // if the board is not set, or query failed redirect to home
    if(!isset($board) || !$board) {
      Flight::redirect("/");
    }
    else {
      //place the posted move
      $result = $board->placeMove($request->data['x'], $request->data['y']);

      //if the result is an array, it means we have a winner
      if(isset($result) && is_array($result)) {
        // delete the save game from our DB
        Flight::db()->delete($request->data['id']);

        // render with winner
        return Flight::render('main-wrapper', 
          array(
            'content' => 'play.php',
            'username' => $request->data['username'],
            'winner' => $result,
            'id'  => $request->data['id'],
            'board' => $board));    
      }
      else if($result) {
        // only save the game state if it is a successful move
        Flight::db()->save($request->data['id'], $board);  
      }

      // render the game page with the current board
      return Flight::render('main-wrapper', 
        array(
          'content' => 'play.php',
          'username' => $request->data['username'],
          'id'  => $request->data['id'],
          'board' => $board));
    }
  }
  else {
    Flight::redirect("/");
  }
});

Flight::start();
?>
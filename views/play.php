 <!-- make sure board is set -->
<?php
if(!isset($board) || gettype($board) != 'object') {
   Flight::redirect('/');
} 
?>

<ul class="breadcrumb">
  <li><a href="/">Home</a> <span class="divider">/</span></li>
  <li><a href="#">Play</a> <span class="divider">/</span></li>
</ul>

<div class="container text-center" id="game">
  
  <!-- display the welcome message if loaded from home.php -->
  <div class="span12 alert alert-success <?php if(!isset($welcome)){ echo 'hide'; }?>">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    Enjoy your stay <em><?php echo "$username" ?>!</em>
  </div>

  <!-- stats section to show winner, current turn, scores -->
  <div class"row">
  <div class="span3" id="stats">
    <p>
      <h4 class="text-info">=Turn=</h4>
      <h5><?php echo ucwords($board->getTurn());?></h5>
    <h4 class="text-info">=Score=</h4>
    <?php
      $white = $board->getScore("white");
      $black = $board->getScore("black");

      if($white == $black) {
        $white = "text-warning";
        $black = "text-warning";
      }
      else if($white > $black) {
        $white = "text-success";
        $black = "text-error";
      }
      else {
        $white = "text-error";
        $black = "text-success";          
      }
      echo '<h5 class="' . $black . '">Black: ' . $board->getScore("black") . '</h5>';
      echo '<h5 class="'. $white . '">White: ' . $board->getScore("white") . '</h5>';
    ?>
   </p>
   <?php 
    if(isset($winner)) { 
      echo '<h4 class="text-info">=Winner=</h4> <h5 class="text-sucess">' . ucwords($winner['winner']) . '</h5>';
    }?>

    <!-- hidden form to hold post params -->
    <form id="coords" name="coords" action="/play" method="POST">
          <input type="hidden" name="id" value="<?php echo "$id" ?>"><br>
          <input type="hidden" name="username" value="<?php echo "$username" ?>"><br>
          <input type="hidden" name="x" id="x"><br>
          <input type="hidden" name="y" id="y"><br>
          <input id="coords-submit" class="hide btn btn-primary" type="submit" value="Play move">
    </form>
</div>

  <div class="span6 text-center hero-unit" id="board-container">
    <table id="board">
    <?php echo $board->draw();?>
     </table>
  </div>

</div> <!-- /container --> <!-- /container -->

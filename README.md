Othello implementation using Flight PHP micro-framework, Twitter Bootstrap, jQuery and SQLite3.

Requires:
<br />1. Flight (included)
<br />2. PHP >= 5.3

Installation:
<br />1. Move all files from \othello to your web directory.
<br />2. Configure your webserver (.htaccess file included).

For Apache, edit your .htaccess file with the following:

<br />RewriteEngine On
<br />RewriteCond %{REQUEST_FILENAME} !-f
<br />RewriteCond %{REQUEST_FILENAME} !-d
<br />RewriteRule ^(.*)$ index.php [QSA,L]

For Nginx, add the following to your server declaration:

server {
    <br />location / {
        <br />try_files $uri $uri/ /index.php;
    }
}

Usage:
<br />1. Navigate to your web directory.
<br />2. Enter a username to load a saved game.
<br />3. Enjoy!

Edited files:
 <br /> OthelloBoard.php
  <br />SaveDB.php
  <br />index.php
  <br />/assets/css/styles.css
  <br />/assets/js/othello.js
  <br />/views/home.php
  <br />/views/play.php
  <br />/views/nav-bar.php
  <br />/views/main-wrapper.php
  <br />/assets/img/black.png
  <br />/assets/img/white.png
  <br />/assets/img/board.png
  <br />/data/othello.db
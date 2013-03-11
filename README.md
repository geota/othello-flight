Othello implementation using Flight PHP micro-framework, Twitter Bootstrap, jQuery and SQLite3.

Requires:
1. Flight (included)
2. PHP >= 5.3

Installation:
1. Move all files from \othello to your web directory.
2. Configure your webserver (.htaccess file included).

For Apache, edit your .htaccess file with the following:

RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

For Nginx, add the following to your server declaration:

server {
    location / {
        try_files $uri $uri/ /index.php;
    }
}

Usage:
1. Navigate to your web directory.
2. Enter a username to load a saved game.
3. Enjoy!

Edited files:
  OthelloBoard.php
  SaveDB.php
  index.php
  /assets/css/styles.css
  /assets/js/othello.js
  /views/home.php
  /views/play.php
  /views/nav-bar.php
  /views/main-wrapper.php
  /assets/img/black.png
  /assets/img/white.png
  /assets/img/board.png
  /data/othello.db
<?php
echo <<<HTML
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./css/main.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      rel="icon"
      href="./assets/images/viki-logo-trans.png"
      type="image/x-icon"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,200;0,500;0,600;0,900;1,100;1,500;1,600;1,900&display=swap"
      rel="stylesheet"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap"
      rel="stylesheet"
    />
    <title>Vikiroom</title>
  </head>
  <body>
    <div class="container">
      <div class="container header">
        <div class="header_row">
          <div class="logo">
            <a class="logo_text" href="#">VIKIROOM</a>
          </div>
          <div class="col_nav">
            <a class="nav_link" href="https://play.google.com/store">DOWNLOAD</a>
            <a class="nav_link" href="#">ABOUT</a>
            <a class="nav_link" href="https://www.instagram.com/vikiroom_vr/"
              >CONTACTS</a
            >
          </div>
        </div>
      </div>
      <div class="underheader">
        <div class="underheader_row header_row">
          <div class="underheader_column">
            <div class="adaptive_img">
              <h2>NOT JUST A DATING APP</h2>
              <img
                class="adpt_img"
                src="./assets/images/viki-logo-trans.png"
                alt="#"
              />
            </div>

            <p class="underheader_paragraph">
              Gone are the days when communication, dating and entertainment
              were incompatible.
            </p>
            <p class="underheader_paragraph">
              It's okay to lose yourself for a while. In books, in music, in
              art, in love. Let yourself get lost...
            </p>
            <a class="btn" href="https://play.google.com/store">Download</a>
          </div>
          <img
            class="main_img"
            src="./assets/images/viki-logo-trans.png"
            alt="#"
          />
        </div>
      </div>
    </div>
  </body>
</html>
HTML;
?>
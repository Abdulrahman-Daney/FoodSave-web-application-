
<?php session_start()?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Food Save</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto|Montserrat"
    />
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body>
  <?php include('header.php') ?>
    <main>
      <section class="hero">
        <div class="hero-content">
          <h1>Let's<br />Donate<br />Together</h1>
          <button class="contact-us" onclick="contactUs()">Contact Us</button>
        </div>
        <img src="hero.png" alt="Hero Image" />
      </section>
      <section class="why-us">
        <img src="whyus.png" alt="Why Us Image" />
        <div class="why-us-content">
          <h2>Why Us</h2>
          <p>
            Food Save offers a hassle-free solution for restaurants to donate
            surplus food...
          </p>
         <a href="howwework.php">  <button>Read more</button> </a>  
        </div>
      </section>
    </main>
    <footer>
      <p>&copy; 2024 Food Save. All rights reserved.</p>
    </footer>
    <script src="script.js"></script>
  </body>
</html>

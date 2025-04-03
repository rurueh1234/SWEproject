
<?php
session_start();
if (!isset($_SESSION["username"])) {
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search Station - MetroX</title>
    <link rel="stylesheet" href="styles.css" />
    <script defer src="scripts.js"></script>
  </head>
  <body>
    <script>
      document.getElementById("search-form").addEventListener("submit", function (event) {
        event.preventDefault();
        const location = document.getElementById("location").value;
        document.getElementById("search-result").innerHTML = `
              <h3>Nearest Station</h3>
              <p>Location: ${location}</p>
              <p>Station: Central Station (2.5 km away)</p>
              <p>Status: <span class="status-indicator status-on-time">On Time</span></p>
          `;
      });

      document.getElementById("use-location").addEventListener("click", function () {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(
            (position) => {
              document.getElementById("search-result").innerHTML = `
                    <h3>Nearest Station</h3>
                    <p>Location: Lat ${position.coords.latitude}, Lon ${position.coords.longitude}</p>
                    <p>Station: Central Station (2.5 km away)</p>
                    <p>Status: <span class="status-indicator status-delayed">Delayed</span></p>
                `;
            },
            (error) => {
              document.getElementById("search-result").innerHTML =
                "<p class='error'>Geolocation failed. Please enter manually.</p>";
            }
          );
        } else {
          alert("Geolocation is not supported by your browser.");
        }
      });
</script>
  </body>
</html>

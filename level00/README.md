LEVEL 00
========

Simple SQL vulnerability - variable namespace not escaped

Type % into the namespace field to retrieve all secrets

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
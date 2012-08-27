LEVEL 02
========

It's not safe to extract all vars from $_GET.
You should read this: [PHP: extract -
Manual](http://php.net/manual/de/function.extract.php)

$attempt == $combination is required, $combination is the content
file_get_contents($filename). Simply manipulate $filename to /dev/null
and you're good to go.

    ?attempt=&filename=/dev/null

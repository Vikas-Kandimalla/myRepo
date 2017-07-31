var http = require("http");

http.createServer(function (request , responce) {
	response.writeHead(200,{'Content-Type' : 'text/html'});
	
	
	respose.end("Hi vikas.You have creates your node js server<br>");
}).listen(8080);


console.log('server running at 8080');
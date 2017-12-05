var http = require('http');
var dt = require('./myfirstmodule');

http.createServer(function (req, res) {
    res.writeHead(200, {'Content-Type': 'text/html'});
    res.end("Supr, datum a čas je: " + dt.myDateTime());
}).listen(8080); 
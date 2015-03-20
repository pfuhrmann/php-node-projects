'use strict';

var express     = require('express'),
    app         = express();

app.use(express.static(__dirname + '/public'));

// Controllers
var index = require('./server/controllers/main');

// Routes
app.get('/', index.index);
app.get('/email', index.email);

// Start server
var server = app.listen(61337, function() {
    console.log('Listening on port %d', server.address().port);
});

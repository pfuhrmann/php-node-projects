'use strict';

var express     = require('express'),
    app         = express();

// Controllers
var api = require('./server/controllers/api');

// Routes
app.get('/sitters', api.sitters);
app.get('/sitters-details', api.sitterDetails);

// Start server
var server = app.listen(61337, function() {
    console.log('Listening on port %d', server.address().port);
});

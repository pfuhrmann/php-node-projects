'use strict';

var express  = require('express'),
    app      = express(),
    mongoose = require('mongoose');

// Connect to MongoDB
mongoose.connect('mongodb://lucius:patres@ds039211.mongolab.com:39211/comp-cw');

// Load Mongoose models
require('./server/models/sitter.js');
require('./server/models/image.js');
require('./server/models/service.js');

// Controllers
var api = require('./server/controllers/api');
var pop = require('./server/controllers/populate');
// Routes
app.get('/sitters', api.sitters);
app.get('/sitters-details', api.sitterDetails);
app.get('/populate', pop.populate);

// Start server
var server = app.listen(61339, function() {
    console.log('Listening on port %d', server.address().port);
});

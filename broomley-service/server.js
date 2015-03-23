'use strict';

var express  = require('express'),
    app      = express(),
    mongoose = require('mongoose');

var SERVER_PATH = './server';

// Connect to MongoDB
mongoose.connect('mongodb://lucius:patres@ds039211.mongolab.com:39211/comp-cw');

// Load Mongoose models
require(SERVER_PATH + '/models/sitter');
require(SERVER_PATH + '/models/image');
require(SERVER_PATH + '/models/service');

// Controllers
var api = require(SERVER_PATH + '/controllers/api');
var pop = require(SERVER_PATH + '/controllers/populate');
// Routes
app.get('/sitters', api.sitters);
app.get('/sitter-details', api.sitterDetails);
app.get('/populate', pop.populate);

app.set('port', (process.env.PORT || 5000));
// Start server
var server = app.listen(app.get('port'), function() {
    console.log('Listening on port %d', server.address().port);
});

'use strict';

var mongoose = require('mongoose'),
    Service = mongoose.model('Service');

/*
* Get sitters
*
* HTTP GET /sitters
*/
exports.sitters = function(req, res) {
    Service.find({}, function (err, docs) {
        res.setHeader('Content-Type', 'application/json');
        res.json(docs);
    });
};

/*
 * Get sitters
 *
 * HTTP GET /sitter-details
 */
exports.sitterDetails = function(req, res) {
    res.send('hello world');
};

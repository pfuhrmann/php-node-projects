'use strict';

var mongoose = require('mongoose'),
    Service = mongoose.model('Service'),
    builder = require('xmlbuilder');

/*
* Get sitters
*
* HTTP GET /sitters
*/
exports.sitters = function(req, res) {
    res.setHeader('Content-Type', 'text/xml');
    var root = builder.create('sitters');

    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    // Type filtering
    var whereQuery;
    if (req.query.type !== undefined && req.query.type != '') {
        whereQuery = {type: new RegExp(req.query.type, 'i')};
    }

    var query = Service.find(whereQuery);
    // Pagination
    if (req.query.limit !== undefined && req.query.limit != '') {
        var limit = req.query.limit;
        var error = '';

        if (!isNumber(limit)) {
            error = 'Limit parameter must be numeric';
            root.ele('error_message', error);
            root = root.end();
            res.send(root);
        }

        var page = 1;
        if (req.query.page !== undefined  && req.query.page != '') {
            page = req.query.page;
            if (!isNumber(page)) {
                error = 'Page parameter must be numeric';
                root.ele('error_message', error);
                root = root.end();
                res.send(root);
            }
        }

        query = query.skip((page - 1) * limit).limit(limit);
    }

    // Query for services
    query.exec(function (err, docs) {
        // Map results to XML

        for (var i = 0; i < docs.length; i++) {
            var service = docs[i];
            // Sitter
            var sitterEl = root.ele('sitter');
            sitterEl.att('id', service._id);
            sitterEl.att('service', 'bromley');
            // Name
            var nameEl = sitterEl.ele('name');
            nameEl.ele('firstname', service.sitter[0].first_name);
            nameEl.ele('lastname', service.sitter[0].last_name);
            // Service
            var serviceEl = sitterEl.ele('service');
            serviceEl.ele('type', service.type);
            serviceEl.ele('charges', service.charges);
            serviceEl.ele('location', service.location);
        }

        root = root.end({ pretty: true});
        res.send(root);
    });
};

/*
 * Get sitters
 *
 * HTTP GET /sitter-details
 */
exports.sitterDetails = function(req, res) {
    res.setHeader('Content-Type', 'text/xml');
    var root = builder.create('sitter_detail');

    // Validate param
    if (req.query.id === null || req.query.id === '') {
        var error = 'ID parameter is required';
        root.ele('error_message', error);
        root = root.end();
        res.send(root);
    }

    // Query for service by specified ID
    Service.findById(req.query.id, function(err, doc) {
        if (err) {
            console.log(err);
        }

        if (!doc) {
            var error = 'Service with given ID was not found';
            root.ele('error_message', error);
            root = root.end();
            res.send(root);
        } else {
            // Map result to XML
            root.att('id', doc._id);
            // Name
            var nameEl = root.ele('name');
            nameEl.ele('firstname', doc.sitter[0].first_name);
            nameEl.ele('lastname', doc.sitter[0].last_name);
            // Contact
            root.ele('email', doc.sitter[0].email);
            root.ele('phone', doc.sitter[0].phone);
            // Service
            var serviceEl = root.ele('service');
            serviceEl.ele('type', doc.type);
            serviceEl.ele('location', doc.location);
            serviceEl.ele('availability', doc.availability);
            serviceEl.ele('description', doc.description);
            serviceEl.ele('charges', doc.charges);
            // Images
            var imgsEl = serviceEl.ele('images');
            for (var i = 0; i < doc.images.length; i++) {
                var image = doc.images[i];
                var imgEl = imgsEl.ele('image');
                imgEl.ele('image_url', image.code);
            }

            root = root.end({ pretty: true});
            res.send(root);
        }
    });
};

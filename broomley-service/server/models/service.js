'use strict';

var mongoose = require('mongoose'),
    Schema = mongoose.Schema,
    ObjectId = Schema.ObjectId;

var Images = mongoose.model('Image').schema;
var Sitter = mongoose.model('Sitter').schema;

var serviceSchema = new Schema({
    id: ObjectId,
    type: String,
    location: String,
    availability: String,
    description: String,
    charges: Number,
    images: [Images],
    sitter: [Sitter]
});

module.exports = mongoose.model('Service', serviceSchema);

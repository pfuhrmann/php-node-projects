'use strict';

var mongoose = require('mongoose'),
    Schema = mongoose.Schema,
    ObjectId = Schema.ObjectId;

var imageSchema = mongoose.model('ImageM').schema;
var sitterSchema = mongoose.model('sitterSchema').schema;

function toMonetary(v) {
    return v.toFixed(2);
}

var serviceSchema = new Schema({
    id: ObjectId,
    type: String,
    location: String,
    availability: String,
    description: String,
    charges: {type: Number, get: toMonetary},
    images: [imageSchema],
    sitter: [sitterSchema]
});

module.exports = mongoose.model('Service', serviceSchema);

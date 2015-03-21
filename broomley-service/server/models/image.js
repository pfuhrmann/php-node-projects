'use strict';

var mongoose = require('mongoose'),
    Schema = mongoose.Schema,
    ObjectId = Schema.ObjectId;

var imageSchema = new Schema({
    id: ObjectId,
    code: String
});

module.exports = mongoose.model('Image', imageSchema);

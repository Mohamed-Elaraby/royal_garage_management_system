"use strict";
const app = require('express')();
const http = require('http').Server(app);
const io = require("socket.io")(http, {
    cors: {
        origin: "https://skbmw-system.com",
        methods: ["GET", "POST"]
    }
});

io.on('connection', function (socket) {

    socket.on('notification', function (arg) {
        io.emit('pushNotification', arg);
    });

    socket.on('close', function () {
        console.log('close');
        io.close();
    });
});
http.listen(3000);

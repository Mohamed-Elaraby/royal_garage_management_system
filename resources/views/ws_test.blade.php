<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>يومية</title>
</head>
<style>
    .items, .items th, .items td,
    .notes_table, .notes_table th, .notes_table td
    {
        border: #0a0a0a 1px solid;
        border-collapse: collapse;
    }
    .footer-info td{
        padding-left: 10px;
    }
    .lightgrey
    {
        background-color: lightgrey;
    }
    .report-container {
        page-break-after:always;
    }
    .repair_order
    {
        padding: 5px 2px;
        border: 1px solid #000;
        text-align: center;
        width: 200px;
        margin: 0 auto;
    }
    body {

        direction: rtl;
        font-size: 10px;
    }
    @page {
        header: page-header;
        footer: page-footer;
    }
</style>
<body>
<input type="text" id="message">
<input type="submit" value="Send Notification">
<input type="button" value="Close" id="close">
<div id="show_notification"></div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdn.socket.io/4.7.2/socket.io.min.js" integrity="sha384-mZLF4UVrpi/QTWPA7BjNPEnkIfRFn4ZEO3Qt/HFklTJBj/gBOV8G3HcKn4NfQblz" crossorigin="anonymous"></script>
<script>
    const socket = io('https://skbmw-system.com:3000');

    $message = $('#message').val();
    // socket.on('notification', function () {
    //     console.log('hi')
    $('input[type="submit"]').on('click', function () {
        $message = $('#message').val();
        socket.emit('notification', $message);
        $('#message').val('');
        if(socket.disconnected)
        {
            console.log('Disconnected');
        }

        if(socket.connected)
        {
            console.log('connected');
        }

    });

    socket.on('pushNotification', function (message) {
        $('#show_notification').text(message).css({
            'background-color': 'black',
            'color': 'green',
            'text-align': 'center',
            'font-size': '50px',
        });
    });

    $('#close').on('click', function () {
        socket.emit('close');
        $('#show_notification').text('')

    });

</script>
</body>
</html>

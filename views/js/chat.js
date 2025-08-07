function message(text) {
    jQuery('#chat-result').append(text);
}

jQuery(document).ready(function($) {
    var socket = new WebSocket("ws://185.69.152.94:8090/server.php");

    socket.onopen = function() {
        message("<div>Соединение установлено</div>");

        var formData = new FormData();
        formData.append('op', 'socketLog');
        formData.append('action', 'Соединение установлено');

        $.ajax({
            url: '/ajax/ajax.php',
            type: "POST",
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(json) {},
            error: function(xhr, ajaxOptions, thrownError) {    
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    };

    socket.onerror = function(error) {
        message("<div>Ошибка при соединении" + (error.message ? error.message : "") + "</div>");

        var formData = new FormData();
        formData.append('op', 'socketLog');
        formData.append('action', 'Ошибка при соединении: ' + (error.message ? error.message : ""));

        $.ajax({
            url: '/ajax/ajax.php',
            type: "POST",
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(json) {},
            error: function(xhr, ajaxOptions, thrownError) {    
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    socket.onclose = function() {
        message("<div>Соединение закрыто</div>");

        var formData = new FormData();
        formData.append('op', 'socketLog');
        formData.append('action', 'Соединение закрыто');

        $.ajax({
            url: '/ajax/ajax.php',
            type: "POST",
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(json) {},
            error: function(xhr, ajaxOptions, thrownError) {    
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    socket.onmessage = function(event) {
        var data = JSON.parse(event.data);
        message("<div>" + data.type + " - " + data.message + "</div>");

        var formData = new FormData();
        formData.append('op', 'socketLog');
        formData.append('action', 'Сообщение: ' + data.message);

        $.ajax({
            url: '/ajax/ajax.php',
            type: "POST",
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(json) {},
            error: function(xhr, ajaxOptions, thrownError) {    
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    $("#chat").on('submit',function() {
        var message = {
            chat_message:$("#chat-message").val(),
            chat_user:$("#chat-user").val(),
        };

        $("#chat-user").attr("type","hidden");

        socket.send(JSON.stringify(message));

        return false;
    });
});
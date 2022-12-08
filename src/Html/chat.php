<style>
    .container-chat {
        margin: 0 auto;
        /* width: 750px; */
        background: #444753;
        border-radius: 5px;
        color: white;
    }
    ul.list {
        max-height: 100%;
        overflow-y: scroll;
        overflow-x: hidden;
    }
    .people-list {
        width: 40%;
        float: left;
    }
    .people-list .search {
        padding: 20px;
    }
    .people-list input {
        border-radius: 3px;
        border: none;
        padding: 14px;
        color: white;
        background: #6a6c75;
        width: 90%;
        font-size: 14px;
    }
    .people-list .fa-search {
        position: relative;
        left: -25px;
    }
    .people-list ul {
        padding: 20px;
        height: 770px;
    }
    .people-list ul li {
        padding-bottom: 20px;
    }
    .people-list img {
        float: left;
    }
    .people-list .about {
        float: left;
        margin-top: 8px;
    }
    .people-list .about {
        padding-left: 8px;
    }
    .people-list .status {
        color: #92959e;
    }
    .chat {
        width: 60%;
        float: left;
        background: #f2f5f8;
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;
        color: #434651;
    }
    .chat .chat-header {
        padding: 20px;
        border-bottom: 2px solid white;
    }
    .chat .chat-header img {
        float: left;
    }
    .chat .chat-header .chat-about {
        float: left;
        padding-left: 10px;
        margin-top: 6px;
    }
    .chat .chat-header .chat-with {
        font-weight: bold;
        font-size: 16px;
    }
    .chat .chat-header .chat-num-messages {
        color: #92959e;
    }
    .chat .chat-header .fa-star {
        float: right;
        color: #d8dadf;
        font-size: 20px;
        margin-top: 12px;
    }
    .chat .chat-history {
        padding: 30px 30px 20px;
        border-bottom: 2px solid white;
        overflow-y: scroll;
        height: 575px;
    }
    .chat .chat-history .message-data {
        margin-bottom: 15px;
    }
    .chat .chat-history .message-data-time {
        color: #a8aab1;
        padding-left: 6px;
    }
    .chat .chat-history .message {
        color: white;
        padding: 18px 20px;
        line-height: 26px;
        font-size: 16px;
        border-radius: 7px;
        margin-bottom: 30px;
        width: 90%;
        position: relative;
    }
    .chat .chat-history .message:after {
        bottom: 100%;
        left: 7%;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
        border-bottom-color: #86bb71;
        border-width: 10px;
        margin-left: -10px;
    }
    .chat .chat-history .my-message {
        background: #86bb71;
    }
    .chat .chat-history .other-message {
        background: #94c2ed;
    }
    .chat .chat-history .other-message:after {
        border-bottom-color: #94c2ed;
        left: 93%;
    }
    .chat .chat-message {
        padding: 30px;
    }
    .chat .chat-message textarea {
        width: 100%;
        border: none;
        padding: 10px 20px;
        font: 14px/22px "Lato", Arial, sans-serif;
        margin-bottom: 10px;
        border-radius: 5px;
        resize: none;
    }
    .chat .chat-message .fa-file-o, .chat .chat-message .fa-file-image-o {
        font-size: 16px;
        color: gray;
        cursor: pointer;
    }
    .chat .chat-message button {
        float: right;
        color: #94c2ed;
        font-size: 16px;
        text-transform: uppercase;
        border: none;
        cursor: pointer;
        font-weight: bold;
        background: #f2f5f8;
    }
    .chat .chat-message button:hover {
        color: #75b1e8;
    }
    .online, .offline, .me {
        margin-right: 3px;
        font-size: 10px;
    }
    .online {
        color: #86bb71;
    }
    .offline {
        color: #e38968;
    }
    .me {
        color: #94c2ed;
    }
    .align-left {
        text-align: left;
    }
    .align-right {
        text-align: right;
    }
    .float-right {
        float: right;
    }
    .clearfix:after {
        visibility: hidden;
        display: block;
        font-size: 0;
        content: " ";
        clear: both;
        height: 0;
    }
    .d-none {
        display: none;
    }

    .avatar {
        width: 52px;
        border-radius: 100%;
    }
    ul {
        list-style-type: none;
    }
</style>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.0/handlebars.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/list.js/1.1.1/list.min.js"></script>

<input type="hidden" id="meId" value="<?php echo $currentUserId ?>">

<div class="container container-chat clearfix">
    <div class="people-list" id="people-list">
      <div class="search">
        <input type="text" placeholder="search" />
        <i class="fa fa-search"></i>
      </div>
      <ul class="list">
        <?php 
            foreach ($users as $user) {
        ?>
            <li class="clearfix" onclick="openChat(<?php echo $user['id'] ?>, '<?php echo $user['name'] ?>', <?php echo $currentUserId ?>)">
                <img src="https://www.pngkey.com/png/detail/121-1219231_user-default-profile.png" class="avatar" alt="avatar" />
                <div class="about">
                    <div class="name"><?php echo $user['name'] ?></div>
                    <div class="status">
                    <?php echo $user['office'] ?>
                    </div>
                </div>
            </li>
        <?php
            }
        ?>
      </ul>
    </div>
    
    <div class="chat">
        <div id="chat_active_header">
            
        </div>
      
      <div class="chat-history">
        <ul>
          
          
        </ul>
        
      </div> <!-- end chat-history -->
      
      <div class="chat-message clearfix d-none">
        <textarea name="message-to-send" id="message-to-send" placeholder ="Digite ..." rows="3"></textarea>
        <input type="hidden" id="receiverId">
                
        <label for="anexo">
            <input type="file" id="anexo" class="d-none" multiple>
            <i class="fa fa-file-o"></i>
        </label>
        
        <button onclick="sendMessage()">Enviar</button>

      </div> <!-- end chat-message -->
      
    </div> <!-- end chat -->
    
  </div> <!-- end container -->

<script>
    (function(){
    
    // var searchFilter = {
    //     options: { valueNames: ['name'] },
    //     init: function() {
    //     var userList = new List('people-list', this.options);
    //     var noItems = $('<li id="no-items-found">No items found</li>');
        
    //     userList.on('updated', function(list) {
    //         if (list.matchingItems.length === 0) {
    //             $(list.list).append(noItems);
    //         } else {
    //             noItems.detach();
    //         }
    //     });
    //     }
    // };
    
    // searchFilter.init();

    $('#anexo').on('change', function() {
        var files = $(this)[0].files;
        $.each(files, function(index, file) {
            sendFile(file);
        });
    });
  
})();

var socket  = new WebSocket('ws://localhost:8080');
setTimeout(() => {
    socket.send('{"user_id": "<?php echo $currentUserId ?>"}');
}, 1000);

function transmitMessage() {
    socket.send( message.value );
}

socket.onmessage = function(e) {
    const res = JSON.parse(e.data);
    if (res.type == 'new_message') {
        newMessage(res);
    }

    if (res.type == 'me_message') {
        meMessage(res);
    }

    if (res.type == 'all_messages') {
        loadMessages(res.messages)
    }
}

function sendFile(file) {
    var receiverId = $('#receiverId').val();
    var meId = $('#meId').val();

    var reader = new FileReader();

    var rawData = new ArrayBuffer();            

    reader.loadend = function() {

    }

    reader.onload = function(e) {

        rawData = reader.result;
        // console.log(reader.result);

        socket.send('{"receiverId": '+receiverId+', "meId": '+meId+',"type":"anexo", "name":"'+file.name+'", "file":"'+rawData+'"}');
    }

    reader.readAsDataURL(file);
}

function newMessage(res) {
    var message = res.message;
    if (res.anexo) {
        message = '<a href="'+res.anexo+'" download>'+res.anexo_name+'</a>'
    }
    var messagens = '<li>\
            <div class="message-data">\
              <span class="message-data-name"><i class="fa fa-circle"></i> '+res.name+'</span>\
              <span class="message-data-time">'+res.created_at+'</span>\
            </div>\
            <div class="message my-message">\
              '+message+'\
            </div>\
          </li>';

    $('.chat-history ul').append(messagens);
    $(".chat-history").animate({ scrollTop: $('.chat-history').height() });
}


function meMessage(res) {
    var message = res.message;
    if (res.anexo) {
        message = '<a href="'+res.anexo+'" download>'+res.anexo_name+'</a>'
    }
    var messagens = '<li>\
            <div class="message-data align-right">\
            <span class="message-data-name"><i class="fa fa-circle"></i> Eu</span>\
            <span class="message-data-time">'+res.created_at+'</span>\
            </div>\
            <div class="message other-message">\
            '+message+'\
            </div>\
        </li>';

    $('.chat-history ul').append(messagens);
    $(".chat-history").animate({ scrollTop: $('.chat-history').height() });
}

function loadMessages(messages) {
    var meId = $('#meId').val();
    $('.chat-history ul').empty();
    $.each(messages, function(index, value) {
        if (meId == value.sender_id) {
            var message = value.message;
            if (value.anexo) {
                message = '<a href="'+value.anexo+'" download>'+value.anexo_name+'</a>'
            }
            
            var messagens = '<li>\
                <div class="message-data align-right">\
                <span class="message-data-name"><i class="fa fa-circle"></i> Eu</span>\
                <span class="message-data-time">'+value.created_at+'</span>\
                </div>\
                <div class="message other-message">\
                '+message+'\
                </div>\
            </li>';

            $('.chat-history ul').append(messagens);
        } else {
            newMessage(value);
        }
    });
    $(".chat-history").animate({ scrollTop: $('.chat-history').height() });
}

function download(id) {
    var receiverId = $('#receiverId').val();
    var meId = $('#meId').val();

    socket.send('{"receiverId": '+receiverId+', "meId": '+meId+',"type":"anexoDownload", "id":"'+id+'"}');
}

function sendMessage() {
    var receiverId = $('#receiverId').val();
    var message = $('#message-to-send').val().replace(/(\r\n|\n|\r)/gm, "");
    var meId = $('#meId').val();

    socket.send('{"receiverId": '+receiverId+', "meId": '+meId+', "message":"'+message+'", "type": "sendMessage"}');

    let date = new Date().toLocaleDateString();

    var messagens = '<li>\
            <div class="message-data align-right">\
              <span class="message-data-name"><i class="fa fa-circle"></i> Eu</span>\
              <span class="message-data-time">'+date+'</span>\
            </div>\
            <div class="message other-message">\
              '+message+'\
            </div>\
          </li>';

    $('.chat-history ul').append(messagens);

    $('#message-to-send').val('');
    $(".chat-history").animate({ scrollTop: $('.chat-history').height() });
}

function openChat(receiverId, receiverName, meId) {
    socket.send('{"receiverId": '+receiverId+', "meId":'+meId+', "type": "allMessages"}');
    $('#receiverId').val(receiverId);
    $('#message-to-send').val('');
    $('.chat-message').removeClass('d-none');

    var htmlHeader = '<div class="chat-header clearfix">\
                <img src="https://www.pngkey.com/png/detail/121-1219231_user-default-profile.png" class="avatar" alt="avatar" />\
                <div class="chat-about">\
                <div class="chat-with">'+receiverName+'</div>\
                </div>\
            </div>';

    $('#chat_active_header').empty();
    $('#chat_active_header').append(htmlHeader);
} 

</script>

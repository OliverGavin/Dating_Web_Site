<?php

?>

    </div><!-- #content -->

    <div id="chat-container">
        <script>
            function open_chat(target_user_id) {
                event.preventDefault();
                if (!$('#chat-'+target_user_id).length) {
                    $.post('ajax/get_chat.php', {id: target_user_id, res: 'get_chat'}, function (data) {
                        // Callback function
                        if (data != 'failed') {
                            $('#chat-container').append(data);
                            scroll_to_message(target_user_id, 0);
                            $('#message-'+target_user_id+' .profile-notification-counter').remove();

                            setInterval(function() { get_new_chat_messages(target_user_id); }, 5000);
                        }
                    });
                }

            }

            function get_new_chat_messages(target_user_id) {
                // TODO improve efficiency
                $.post('ajax/get_chat.php', {id: target_user_id, res: 'get_chat_messages'}, function (data) {
                    // Callback function
                    if (data != 'failed') {
                        $('#chat-messages-'+target_user_id).html(data);
                    }
                });
            }

            function close_chat(id) {
                $('#chat-'+id).remove();
            }

            function send_message(el, target_user_id) {
                event.preventDefault();
                var msg_input = $(el).find('.message-input');
                $.post('ajax/get_chat.php', {id:target_user_id, action:'send', message:msg_input.val()}, function(data) {
                    // Callback function
                    if (data != 'failed') {
                        get_new_chat_messages(target_user_id);
                        msg_input.val('');
                    }
                    msg_input.focus();
                    scroll_to_message(target_user_id, 500);
                });

                return false;
            }

            function scroll_to_message(id, speed){
                var element = $('#chat-messages-'+id);
                $(element).animate({ scrollTop: $(element).prop("scrollHeight")}, speed);
            }

        </script>
        <script>
            setInterval(start_content_service, 5000);

            function start_content_service() {
//                alert('hhh');
            }
        </script>
    </div>

    <footer id="main-footer" class="site-footer" role="contentinfo">
        <div class="site-wrapper">
            <div class="site-info">

            </div>
        </div>
    </footer>
</div><!-- #page -->


</body>
</html>

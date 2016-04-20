<?php

?>

    </div><!-- #content -->

    <div id="chat-container">
        <script>
            var loading = false;

            function open_chat(target_user_id) {
                event.preventDefault();
                $('#main').css({ "cursor": "wait" });
                if (!$('#chat-'+target_user_id).length) {
                    $.post('ajax/get_chat.php', {id: target_user_id, res: 'get_chat'}, function (data) {
                        // Callback function
                        if (data != 'failed') {
                            $('#chat-container').append(data);
                            scroll_to_message(target_user_id, 0);
                            var message_unseen_count = $('#message-'+target_user_id+' .profile-notification-counter p').html();
                            var total_message_unseen_count = $('#messages-unseen-counter p').html();
                            var new_total_message_unseen_count = total_message_unseen_count - message_unseen_count;
                            if (message_unseen_count > 0)
                                $('#messages-unseen-counter p').html(new_total_message_unseen_count);
                            if ((new_total_message_unseen_count == 0) || (new_total_message_unseen_count = "0"))
                                $('#messages-unseen-counter').remove();

                            $('#message-'+target_user_id+' .profile-notification-counter').remove();

                            setInterval(function() { get_new_chat_messages(target_user_id); }, 10000);
                        }
                        $('#main').css({ "cursor": "" });
                    });
                }

            }

            function get_new_chat_messages(target_user_id) {
                if (loading) return;
                loading = true;
                // TODO improve efficiency
                $.post('ajax/get_chat.php', {id: target_user_id, res: 'get_chat_messages'}, function (data) {
                    // Callback function
                    if (data != 'failed') {
                        $('#chat-messages-'+target_user_id).html(data);
                    }
                    loading = false;
                });
            }

            function close_chat(id) {
                $('#chat-'+id).remove();
            }

            function send_message(el, target_user_id) {
                event.preventDefault();
                $('#chat-'+target_user_id).css({ "cursor": "wait" });
                var msg_input = $(el).find('.message-input');
                $.post('ajax/get_chat.php', {id:target_user_id, action:'send', message:msg_input.val()}, function(data) {
                    // Callback function
                    if (data != 'failed') {
                        get_new_chat_messages(target_user_id);
                        msg_input.val('');
                    }
                    msg_input.focus();
                    scroll_to_message(target_user_id, 500);
                    $('#chat-'+target_user_id).css({ "cursor": "" });
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

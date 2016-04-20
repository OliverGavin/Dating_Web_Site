<?php if ($_POST['res'] == 'get_chat' && $_POST['res'] != 'get_chat_messages') { ?>
    <div id="chat-<?=$user_id?>" class="chat">
        <div class="chat-header">
            <img class="chat-pic" src="<?=get_profile_image(IMG_THUMB, $user_id)?>">
            <p class="chat-title"><?=$user->first_name?> <?=$user->last_name?> <span>(<?=get_user_role_name($user_id)?>)</span></p>
            <span class="chat-close" onclick="close_chat(<?=$user_id?>)"><i class="fa fa-times"></i></span>
        </div>
        <div id="chat-messages-<?=$user_id?>" class="chat-messages scroll">
<?php } ?>
            <?php
            $messages = get_messages($user_id);

            if ($messages) {
                foreach ($messages as $message) {
                    if ($message->sender_id == $_SESSION['user_id']) {
                        $type = 'sent';
                    } else {
                        $type = 'received';}
                    ?>
                    <div class="message message-<?=$type?>"><p><?= $message->content ?></p></div>
                    <?php
                    }
                    set_messages_from_user_seen($user_id);
            }
            ?>
<?php if ($_POST['res'] == 'get_chat' && $_POST['res'] != 'get_chat_messages') { ?>
        </div>
        <div class="chat-input">
            <form action="chat.php" method="post" class="style-rounded-dark" onsubmit="return send_message(this, <?=$user_id?>)">
                <input class="message-input" type="text" placeholder="type here...">
                <button type="submit"><i class="fa fa-paper-plane"></i></button>
            </form>
        </div>
    </div>
<?php } ?>

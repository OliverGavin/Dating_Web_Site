
<?php
$likes = get_interests($user_id, true);

echo '<div class="profile-field profile-interests profile-likes">';
    echo '<label for="new_interest_like">Likes</label>';

    echo '<ul style="list-style: none">';
    if (isset($likes) && !empty($likes)) {

        foreach ($likes as $like) {
            echo '<li class="interest interest-like">';
                // link is a rollback if JS is not present
                $link = $_SERVER['REQUEST_URI'] . (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])? '&':'?') . 'delete_interest=' . $like->interests_id;
                echo '<a href="' . $link . '" class="interest-action interest-delete" onclick="remove_interest(this,'.$like->interests_id.')" title="delete">';
                    echo '<i class="fa fa-times"></i>';
                echo '</a>';
                echo '<span class="interest-content">';
                    echo $like->content;
                echo '</span>';

            echo '</li>';
        }
    }
        echo '<li><input type="text" id="new_interest_like" name="new_interest_like" value="" /></li>';
    echo '</ul>';

echo '</div>';
?>


<?php
$dislikes = get_interests($user_id, false);

echo '<div class="profile-field profile-dislikes">';
    echo '<label for="new_interest_dislike">Dislikes</label>';

    echo '<ul style="list-style: none">';
    if (isset($dislikes) && !empty($dislikes)) {

            foreach ($dislikes as $dislike) {
                echo '<li class="interest interest-like">';
                    // link is a rollback if JS is not present
                    $link = $_SERVER['REQUEST_URI'] . (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])? '&':'?') . 'delete_interest=' . $dislike->interests_id;
                    echo '<a href="' . $link . '" class="interest-action interest-delete" onclick="remove_interest(this,'.$dislike->interests_id.')" title="delete">';
                        echo '<i class="fa fa-times"></i>';
                    echo '</a>';
                    echo '<span class="interest-content">';
                        echo $dislike->content;
                    echo '</span>';

                echo '</li>';
            }
        }
        echo '<li><input type="text" id="new_interest_dislike" name="new_interest_dislike" value="" /></li>';
    echo '</ul>';

echo '</div>';
?>
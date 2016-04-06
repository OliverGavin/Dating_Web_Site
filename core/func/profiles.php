<?php

/**
 * Class Profile represents a users profile
 */
class Profile {

    public $user_id;
    public $first_name;
    public $last_name;
    public $DOB_day;
    public $DOB_month;
    public $DOB_year;
    public $DOB;
    public $age;
    public $sex;
    public $description;
    public $country;
    public $county;
    public $looking_for;
    public $min_age;
    public $max_age;
    public $date_time_updated;

    public $error = false;

    public function __construct($user_id) {
        $this->user_id = $user_id;
    }

    public function error_push($error) {
        if (!$this->error) {
            $this->error = array();
        }
        array_push($this->error, $error);
    }

    function create_profile() {
        global $db;

        $this->DOB_day        =   $_POST['DOB_day'];
        $this->DOB_month      =   $_POST['DOB_month'];
        $this->DOB_year       =   $_POST['DOB_year'];
        $this->DOB            =   "$this->DOB_year-$this->DOB_month-$this->DOB_day";
        $this->sex            =   $_POST['sex'];

        $prepared = $db->prepare("
                INSERT INTO profiles (user_id, DOB, sex, date_time_updated)
                VALUES (?, ?, ?, NOW())
            ");

        $prepared->bind_param('isi', $this->user_id, $this->DOB, $this->sex); //s - string

        if (!$prepared->execute()) {
            $this->error_push(ERROR);
            return;
        }

        return;
    }

    public function fetch() {
        global $db;

        $prepared = $db->prepare("
              SELECT    first_name, last_name,
                        DOB, sex, description, country,
                        county, looking_for, min_age,
                        max_age, date_time_updated
              FROM users NATURAL JOIN profiles
              WHERE user_id = ?
            ");

        $prepared->bind_param('s', $this->user_id);

        if (!$prepared->execute()) {
            $this->error_push(ERROR);
            return false;
        }

        $prepared->bind_result(
            $this->first_name,
            $this->last_name,
            $DOB,
            $this->sex,
            $this->description,
            $this->country,
            $this->county,
            $this->looking_for,
            $this->min_age,
            $this->max_age,
            $this->date_time_updated
        );

        if (!$prepared->fetch()) {
            $this->error_push(NOT_FOUND);
            return false;
        }

        $this->DOB = date_create($DOB);
        $this->DOB_year = $this->DOB->format('Y');
        $this->DOB_month = $this->DOB->format('m');
        $this->DOB_day = $this->DOB->format('d');

        $this->age = date_diff($this->DOB, date_create('now'))->y;

        return true;
    }

    // Updates a profile for a given user_id
    public function submit() {
        global $db;

        // if the profile is not found, a profile will be created
        if (!exists_profile($this->user_id)) {
            $this->create_profile();
        }

        // Submit changes to DB
        // TODO remove and use Profile?
        $this->first_name     =   $_POST['first_name'];
        $this->last_name      =   $_POST['last_name'];
        $this->DOB_day        =   $_POST['DOB_day'];
        $this->DOB_month      =   $_POST['DOB_month'];
        $this->DOB_year       =   $_POST['DOB_year'];
        $this->DOB            =   "$this->DOB_year-$this->DOB_month-$this->DOB_day";
        $this->sex            =   $_POST['sex'];
        $this->description    =   $_POST['description'];
        $this->country        =   $_POST['country'];
        $this->county         =   $_POST['county'];
        $this->looking_for    =   $_POST['looking_for'];
        $this->min_age        =   $_POST['min_age'];
        $this->max_age        =   $_POST['max_age'];

        $prepared = $db->prepare("
              UPDATE users
              SET first_name = ?, last_name = ?
              WHERE user_id = ?
            ");

        $prepared->bind_param('ssi', $this->first_name, $this->last_name, $this->user_id);

        if (!$prepared->execute()) {
            $this->error_push(ERROR);
            return false;
        }

        $prepared = $db->prepare("
              UPDATE profiles
              SET DOB = ?, sex = ?, description = ?,
                  country = ?, county = ?, looking_for = ?, min_age = ?, max_age = ?,
                  date_time_updated = NOW()
              WHERE user_id = ?
            ");

        $prepared->bind_param('sisssiiii',
            $this->DOB,
            $this->sex,
            $this->description,
            $this->country,
            $this->county,
            $this->looking_for,
            $this->min_age,
            $this->max_age,
            $this->user_id
        );

        if (!$prepared->execute()) {
            $this->error_push(ERROR);
            return false;
        }

        if ($this->user_id == $_SESSION['user_id']) {
            $_SESSION['first_name'] = $this->first_name;
            $_SESSION['last_name'] = $this->last_name;
        }

        return true;
    }

}


/**
 * Gets the profile of a user
 * @param $user_id
 * @return bool|Profile
 */
function get_profile($user_id) {
    global $message;

    if (!exists_profile($user_id)) {
        $message['error'][] = NOT_FOUND;
        return false;
    }

    $is_blocked_by_owner = false;           // TODO
    // Unauthorised user - blocked
    if ($is_blocked_by_owner) {
        $message['error'][] = BLOCKED;
        return false;
    }

    if (!user_can(PERM_VIEW_PROFILES)) {
        // Authorised, but not permitted to view (upgrade required)
        $message['error'][] = MSG_UPGRADE_REQUIRED;
        return false;
    }

    $profile = new Profile($user_id);
    $profile->fetch();

    if ($profile->error) {
//        header("Location: 404.php");      // TODO
        return false;
    }

    return $profile;

}

function submit_profile() {

}

/**
 * Checks if a profile exists
 * @param $user_id
 * @return bool
 */
function exists_profile($user_id) {
    global $db;
    global $message;

    $prepared = $db->prepare("
            SELECT `user_id`
            FROM `profiles`
            WHERE user_id=?
    ");

    $prepared->bind_param("i", $user_id);

    if (!$prepared->execute()) {
        $message['error'][] = ERROR;
        return false;
    }

    $prepared->store_result();
    $prepared->bind_result($id);
    $prepared->fetch();

    if ($prepared->num_rows != 1){
        $message['error'][] = NOT_FOUND;
        return false;
    }

    return true;
}

/**
 * Deletes a profile
 * @param $user_id
 * @return bool
 */
function delete_profile($user_id) {
    global $message;

    // TODO permissions
    if (!exists_profile($user_id)) {
        $message['error'][] = NOT_FOUND;
        return false;
    }

    global $db;

    $prepared = $db->prepare("
            DELETE FROM `profiles`
            WHERE user_id=?
    ");

    $prepared->bind_param("i", $user_id);

    if(!$prepared->execute()){
        $message['error'][] = ERROR;
        return false;
    }

    return true;

}

function get_all_profiles() {
    return get_profiles('', array(), '', '', '');
}

/**
 * Gets profiles based on the query passed
 * A SQL injection safe query is built using prepared statements
 * @param string $query_stmt_parts         SQL in WHERE clause
 * @param array  $query_param_values       list of values to bind
 * @param string $query_param_types        value types e.g. 'issis'
 * @param string $query_join_parts         SQL in JOIN clause
 * @param string $query_end_parts          SQL after WHERE clause
 * @return array|boolean                   false on error
 */
function get_profiles($query_stmt_parts, $query_param_values, $query_param_types, $query_join_parts, $query_end_parts) {
    global $db;
    global $message;
    // TODO refactor
    if (!user_can(PERM_VIEW_PROFILES)) {
        $message['error'][] = MSG_PERMISSION_DENIED;
        return false;
    }

    // Default
    $query_parts = "";
    $param_values = array($_SESSION['user_id']);
    $param_types = 'i';

    // Check that the query passed has the same amount of params and values
    if (count($query_param_values) > 0
        && count($query_param_values) == substr_count($query_stmt_parts, '?')+substr_count($query_join_parts, '?')) {
            $query_parts  = $query_stmt_parts.' AND ';
            $param_values = array_merge($query_param_values, $param_values);
            $param_types  = $query_param_types . $param_types;
    }

    //First parameter of mysqli bind_param
    $ref_args = array($param_types);
    // bind_param requires parameters to be references rather than values
    // create array of references
    foreach ($param_values as $key => $value)
        $ref_args[] = &$param_values[$key];


    // TODO check if blocked
    $profiles = array();

    // TODO add limit and ignore list??
    $prepared = $db->prepare("
              SELECT    user_id, first_name, last_name,
                        DOB, country, county
              FROM users NATURAL JOIN profiles $query_join_parts
              WHERE $query_parts user_id != ?
              $query_end_parts"
    );

    // calls $prepared->bind_param($ref_args[0], $ref_args[1]... );
    call_user_func_array(array($prepared, 'bind_param'), $ref_args);

    if (!$prepared->execute()) {
        $message['error'][] = ERROR;
        return false;
    }

    $prepared->bind_result(
        $user_id,
        $first_name,
        $last_name,
        $DOB,
        $country,
        $county
    );

    while ($prepared->fetch()) {
        $profile = new Profile($user_id);
        $profile->first_name = $first_name;
        $profile->last_name = $last_name;
        $profile->DOB = date_create($DOB);

        $profile->age = date_diff($profile->DOB, date_create('now'))->y;

        $profile->country = $country;
        $profile->county = $county;

        array_push($profiles, $profile);
    }

    return $profiles;

}






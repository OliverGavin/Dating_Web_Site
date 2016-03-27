<?php
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
            $this->error_push('failed');
            return;
        }

        // TODO error check

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

        $prepared->execute();
        // TODO error detection
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

        $success = $prepared->fetch();

        if (!$success) {
            $this->error_push('Not found???');
            return;
            // TODO error message.. not found
        }

        $this->DOB = date_create($DOB);
        $this->DOB_year = $this->DOB->format('Y');
        $this->DOB_month = $this->DOB->format('m');
        $this->DOB_day = $this->DOB->format('d');

        $this->age = date_diff($this->DOB, date_create('now'))->y;

        return;
    }

    // Updates a profile for a given user_id
    public function submit() {
        global $db;

        // if the profile is not found, a profile will be created
        if (!exists_profile($this->user_id)) {
            $this->create_profile();
        }

        // Submit changes to DB

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
//        $this->date_time_updated  =   date_create()->format("Y-m-d h:i:s");

        // TODO validation? and create
        $prepared = $db->prepare("
              UPDATE users
              SET first_name = ?, last_name = ?
              WHERE user_id = ?
            ");

        $prepared->bind_param('ssi', $this->first_name, $this->last_name, $this->user_id);
        $prepared->execute();

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
//            $this->date_time_updated,
            $this->user_id
        );

        $prepared->execute();

        // TODO errors

        $_SESSION['first_name'] = $this->first_name;
        $_SESSION['last_name'] = $this->last_name;

        return true;
    }

}

function get_profile($user_id) {

    if (!exists_profile($user_id)) {
        //
        return false;
    }

    $is_blocked_by_owner = false;           // TODO
    // Unauthorised user - blocked
    if ($is_blocked_by_owner) {
//        header("Location: 401.php");        //TODO errors
        return false;
    }

    $can_view = true;   // TODO permissions
    // Authorised, but not permitted to view (upgrade required)
    if (!$can_view) {
//        header("Location: upgrade.php");
        return false;
    }

    $profile = new Profile($user_id);
    $profile->fetch();

    if ($profile->error) {
//        header("Location: 404.php");
        return false;
    }

    return $profile;

}

function submit_profile() {

}

function exists_profile($user_id) {
    global $db;

    $prepared = $db->prepare("
            SELECT `user_id`
            FROM `profiles`
            WHERE user_id=?
    ");

    if ($prepared){

        $prepared->bind_param("i", $user_id);

        if($prepared->execute()){
            $prepared->store_result();
            $prepared->bind_result($id);
            $prepared->fetch();

            if ($prepared->num_rows == 1){
                return true;
            }
        }
    }
    return false;
}

function delete_profile($user_id) {
    // TODO permissions
    if (!exists_profile($user_id)) {
        //
        return false;
    }

    global $db;

    $prepared = $db->prepare("
            DELETE FROM `profiles`
            WHERE user_id=?
    ");

    if ($prepared){

        $prepared->bind_param("i", $user_id);

        if($prepared->execute()){
            return true;
        }
    }
    return false;

}

// Gets profiles based on the query passed
// A SQL injection safe query is built using prepared statements
function get_profiles($query_stmt_parts, $query_param_values, $query_param_types) {
    global $db;

    // Default
    $query_parts = "";
    $param_values = array($_SESSION['user_id']);
    $param_types = 'i';

    // Check that the query passed has the same amount of params and values
    if (count($query_param_values) > 0 && count($query_param_values) == substr_count($query_stmt_parts, '?')) {
        $query_parts  = " AND ".$query_stmt_parts;
        $param_values = array_merge($param_values, $query_param_values);
        $param_types  = $param_types . $query_param_types;
    }

    //First parameter of mysqli bind_param
    $ref_args = array($param_types);
    // bind_param requires parameters to be references rather than values
    // create array of references
    foreach ($param_values as $key => $value)
        $ref_args[] = &$param_values[$key];


    // TODO check permissions and if blocked
    $profiles = array();

    // TODO add limit and ignore list??
    $prepared = $db->prepare("
              SELECT    user_id, first_name, last_name,
                        DOB, country, county
              FROM users NATURAL JOIN profiles
              WHERE user_id != ? $query_parts"
    );

    // calls $prepared->bind_param($ref_args[0], $ref_args[1]... );
    call_user_func_array(array($prepared, 'bind_param'), $ref_args);

    $prepared->execute();

    $prepared->store_result();

    if ($prepared->num_rows == 0){
//        TODO
//        error_push('Not found???');
//        return null;
    }

    // TODO error detection
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






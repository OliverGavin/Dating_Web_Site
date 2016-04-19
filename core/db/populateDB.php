<?php
$pathToRoot = '../../';
require_once $pathToRoot.'core/init.php';
require_once $pathToRoot.'core/func/users.php';
require_once $pathToRoot.'core/func/interests.php';
// Data sources:
//  http://listofrandomnames.com/
//  http://www.notsoboringlife.com/list-of-hobbies/


$male_first_names = array(
    'Antone', 'Waldo', 'Marshall', 'Gerry', 'Roger', 'Morris', 'Bennett', 'Kraig', 'Alphonse', 'Dexter', 'Jonathon', 'Fernando', 'Kelley', 'James', 'Rocky', 'Jewell', 'Ezra', 'Tristan', 'Josiah', 'Bernie', 'Justin',
    'Wilmer', 'Columbus', 'Robt', 'Ashley', 'Woodrow', 'Jarod', 'Guillermo', 'Ron', 'Huey', 'Dion', 'Hilario', 'Lenard', 'Lyndon', 'Olen', 'Jc', 'Darryl', 'Sidney', 'Jame', 'Ivan', 'Ellsworth', 'Emery', 'Chang', 'Arlie', 'Joel', 'Blair', 'Graig', 'Micah', 'Derek',
    'Omar', 'Gerald', 'Britt', 'Jeff', 'Frederic', 'Buster', 'Louis', 'Ellsworth', 'Earnest', 'Dennis', 'Arthur', 'Santo', 'Dave', 'Elisha', 'Bryant', 'Danny', 'Boyce', 'Robt', 'Winfred', 'Victor', 'Deon', 'Simon', 'Jan', 'Walker', 'Courtney', 'Carl',
    'Tyree', 'Mauricio', 'Lyndon', 'Guillermo', 'Brenton', 'Son', 'Mose', 'Vincent', 'Sung', 'Alphonso', 'Carey', 'Cody', 'Willie', 'Major', 'Alan', 'Carlton', 'Ezra', 'Russel', 'Vance', 'Korey', 'Loyd', 'Lyle', 'Ivory', 'Johnie', 'Emile');

$female_first_names = array(
    'Lanette', 'Ashlea', 'Tamar', 'Lael', 'Charlotte', 'Arlette', 'Charity', 'Barabara', 'Bailey', 'Sasha', 'Cordelia', 'Hisako', 'Sharmaine', 'Vicenta', 'Deloras', 'Chanell', 'Kathaleen', 'Brandie', 'Dora', 'Laureen', 'Willow', 'Dotty',
    'Dusti', 'Aurea', 'Dollie', 'Dulcie', 'Honey', 'Ginger', 'Jazmine', 'Jeanelle', 'Cathryn', 'Loan', 'Laquanda', 'Jessi', 'Julietta', 'Ethelyn', 'Tiesha', 'Freda', 'Fleta', 'Roselle', 'Tera', 'Sanjuana', 'Takako', 'Kyong',
    'Lu', 'Rubie', 'Cleta', 'Doris', 'Alaine', 'Albina', 'Tanisha', 'Karyl', 'Maryjane', 'Luanna', 'Natalie', 'Hyun', 'Hwa', 'Betty', 'Mistie', 'Davida', 'Eleonora', 'Malika', 'Summer', 'Evette', 'Joanna',
    'Sunni', 'Suzann', 'Noma', 'Darlena', 'Sherrell', 'Christi', 'Sharon', 'Lisha', 'Geralyn', 'Lyn', 'Marilee', 'Felipa', 'Arlinda', 'Mana', 'Janice', 'Corrin', 'Twana', 'Nita', 'Shelly',
    'Roxanna', 'Shemeka', 'Toshiko', 'Kimiko', 'Charmaine', 'Eugenie', 'Deadra', 'Mariette', 'Fatima', 'Raven', 'Adaline', 'Breann', 'Kay', 'Eleanora', 'Lovie', 'Davina');

$last_names = array(
    'Levin', 'Neer', 'Savedra', 'Abalos', 'Voegele', 'Gadberry', 'Roses', 'Ballesteros', 'Liebold', 'Ortego', 'Mannion', 'Kennedy', 'Kottke', 'Dolby', 'Olberding', 'Wetherby', 'Deshields', 'Branco', 'Shupe', 'Elsner', 'Holthaus', 'Perron',
    'Stever', 'Torain', 'Christie', 'Houseman', 'Boren', 'Lounsbury', 'Bitter', 'Nettles', 'Sharber', 'Hust', 'Liller', 'Finkle', 'Polley', 'Hedden', 'Mulholland', 'Savoie', 'Buchner', 'Sumner', 'Fetters', 'Marker', 'Frechette', 'Corley', 'Countess', 'Kierstead', 'Sproles',
    'Madison', 'Duhon', 'Leppert', 'Kueter', 'Montufar', 'Tanner', 'Rubin', 'Hamid', 'Hulme', 'Swope', 'Hooton', 'Reilley', 'Lamore', 'Tiller', 'Mastin', 'Huwe', 'Koth', 'Vittetoe', 'Luzier', 'Bones', 'Romine', 'Eells', 'Faux',
    'Moscoso', 'Curlin', 'Hampton', 'Ambriz', 'Izaguirre', 'Sharp', 'Piner', 'Billman', 'Grisson', 'Mayer', 'Merrit', 'Heyman', 'Marchand', 'Sanford', 'Shulman',
    'Bumbrey', 'Parham', 'Bewley', 'Jock', 'Wengerd', 'Lindsey', 'Hobdy', 'Duvall', 'Stringfield', 'Nicolai', 'Lehmkuhl', 'Akin', 'Prowse', 'Barbagallo', 'Boulay',
    'Claussen', 'Pinkston', 'Leyba', 'Prouty', 'Gartner', 'Melia', 'Rance', 'Cavazos', 'Villani', 'Tisch', 'Clouser', 'Scherr', 'Virgil', 'Munsell', 'Bumpers', 'Leeson', 'Loux', 'Houghton', 'Lemay', 'Jury', 'Cleveland', 'Weyant',
    'Douse', 'Barretta', 'Duane', 'Sheroan', 'Crochet', 'Stone', 'Aker', 'Propp', 'Herlihy', 'Manier', 'Surace', 'Ohalloran', 'Hackley', 'Mccaffrey', 'Lightford', 'Raleigh', 'Coombs', 'Luiz', 'Scheid', 'Oxley', 'Ives',
    'Mcgough', 'Freshour', 'Glancy', 'Heber', 'Chinn', 'Lindo', 'Mccleskey', 'Tarrance', 'Horman', 'Carwile', 'Rivard', 'Mountjoy', 'Vegas', 'Haswell', 'Lan', 'Florence', 'Ricciardi', 'Knowles', 'Mauro',
    'Mcclard', 'Beall', 'Clatterbuck', 'Haan', 'Cann', 'Kosak', 'Litman', 'Armbruster', 'Woodrow', 'Tolleson', 'Agnew', 'Hoefer', 'Tetreault', 'Konkol', 'Go', 'Bones', 'Stigall', 'Villar', 'Tipps', 'Brawn', 'Hickel', 'Querry',
    'Pardon', 'Koeppel', 'Sabol', 'Canney', 'Chapdelaine', 'Trapani', 'Bayerl', 'Larimer', 'Ogle', 'Bachman', 'Howarth', 'Helper', 'Yearout', 'Scully', 'Schoonmaker', 'Cortina');


$interests = array(
    'Aircraft Spotting', 'Airbrushing', 'Airsofting', 'Acting', 'Aeromodeling', 'Amateur Astronomy', 'Amateur Radio', 'Animals/pets/dogs', 'Archery', 'Arts', 'Astrology', 'Astronomy', 'Backgammon', 'Badminton', 'Baseball', 'Base Jumping', 'Basketball', 'Beach/Sun tanning', 'Beachcombing', 'Beadwork', 'Beatboxing',
    'Bell Ringing', 'Belly Dancing', 'Bicycling', 'Bicycle Polo', 'Bird watching', 'Birding', 'BMX', 'Blacksmithing', 'Blogging', 'BoardGames', 'Boating', 'Body Building', 'Bonsai Tree', 'Bookbinding', 'Boomerangs', 'Bowling', 'Brewing Beer',
    'Bridge Building', 'Bringing Food To The Disabled', 'Butterfly Watching', 'Button Collecting', 'Cake Decorating', 'Calligraphy', 'Camping', 'Candle Making', 'Canoeing', 'Cartooning', 'Car Racing', 'Casino Gambling', 'Cave Diving', 'Ceramics', 'Cheerleading', 'Chess', 'Church/church activities', 'Cigar Smoking',
    'Cloud Watching', 'Coin Collecting', 'Collecting', 'Collecting Antiques', 'Collecting Artwork', 'Collecting Hats', 'Collecting Music Albums', 'Collecting RPM Records', 'Collecting Sports Cards', 'Collecting Swords', 'Coloring', 'Compose Music', 'Computer activities', 'Conworlding', 'Cooking',
    'Cosplay', 'Crafts', 'Crochet', 'Crocheting', 'Cross-Stitch', 'Crossword Puzzles', 'Dancing', 'Darts', 'Diecast Collectibles', 'Digital Photography', 'Dodgeball', 'Dolls', 'Dominoes', 'Drawing', 'Dumpster Diving', 'Eating out',
    'Educational Courses', 'Electronics', 'Embroidery', 'Entertaining', 'Exercise', 'aerobics', 'weights', 'Falconry', 'Fast cars', 'Felting', 'Fencing', 'Fire Poi', 'Fishing',
    'Floorball', 'Floral Arrangements', 'Fly Tying', 'Football', 'Four Wheeling', 'Freshwater Aquariums', 'Frisbee Golf – Frolf', 'Games', 'Gardening', 'Garage Saleing', 'Genealogy', 'Geocaching', 'Ghost Hunting', 'Glowsticking', 'Gnoming', 'Going to movies',
    'Golf', 'Go Kart Racing', 'Grip Strength', 'Guitar', 'Gunsmithing', 'Gun Collecting', 'Gymnastics', 'Gyotaku', 'Handwriting Analysis', 'Hang gliding', 'Herping', 'Hiking', 'Home Brewing', 'Home Repair', 'Home Theater', 'Horse riding',
    'Hot air ballooning', 'Hula Hooping', 'Hunting', 'Iceskating', 'Illusion', 'Impersonations', 'Internet', 'Inventing', 'Jet Engines', 'Jewelry Making', 'Jigsaw Puzzles', 'Juggling', 'Keep A Journal', 'Jump Roping', 'Kayaking', 'Kitchen Chemistry', 'Kites', 'Kite Boarding', 'Knitting',
    'Knotting', 'Lasers', 'Lawn Darts', 'Learn to Play Poker', 'Learning A Foreign Language', 'Learning An Instrument', 'Learning To Pilot A Plane', 'Leathercrafting', 'Legos', 'Letterboxing', 'Listening to music', 'Locksport', 'Lacrosse', 'Macramé', 'Magic', 'Making Model Cars', 'Marksmanship', 'Martial Arts', 'Matchstick Modeling', 'Meditation',
    'Microscopy', 'Metal Detecting', 'Model Railroading', 'Model Rockets', 'Modeling Ships', 'Models', 'Motorcycles', 'Mountain Biking', 'Mountain Climbing', 'Musical Instruments', 'Nail Art', 'Needlepoint', 'Owning An Antique Car', 'Origami', 'Painting', 'Paintball', 'Papermaking', 'Papermache',
    'Parachuting', 'Parkour', 'People Watching', 'Photography', 'Piano', 'Pinochle', 'Pipe Smoking', 'Planking', 'Playing music', 'Playing team sports', 'Pole Dancing', 'Pottery', 'Powerboking', 'Protesting', 'Puppetry', 'Pyrotechnics', 'Quilting', 'Racing Pigeons', 'Rafting',
    'Railfans', 'Rapping', 'RC Boats', 'RC Cars', 'RC Helicopters', 'RC Planes', 'Reading', 'Reading To The Elderly', 'Relaxing', 'Renaissance Faire', 'Renting movies', 'Rescuing Animals', 'Robotics', 'Rock Balancing', 'Rock Collecting', 'Rockets', 'Rocking AIDS Babies', 'Roleplaying', 'Running',
    'Saltwater Aquariums', 'Sand Castles', 'Scrapbooking', 'Scuba Diving', 'Self Defense', 'Sewing', 'Shark Fishing', 'Skeet Shooting', 'Skiing', 'Shopping', 'Singing In Choir', 'Skateboarding', 'Sketching', 'Sky Diving', 'Slack Lining', 'Sleeping', 'Slingshots', 'Slot Car Racing', 'Snorkeling', 'Snowboarding',
    'Soap Making', 'Soccer', 'Socializing with friends/neighbors', 'Spelunkering', 'Spending time with family/kids', 'Stamp Collecting', 'Storm Chasing', 'Storytelling', 'String Figures', 'Surfing', 'Surf Fishing', 'Survival', 'Swimming', 'Tatting', 'Taxidermy', 'Tea Tasting', 'Tennis', 'Tesla Coils', 'Tetris',
    'Texting', 'Textiles', 'Tombstone Rubbing', 'Tool Collecting', 'Toy Collecting', 'Train Collecting', 'Train Spotting', 'Traveling', 'Treasure Hunting', 'Trekkie', 'Tutoring Children', 'TV watching', 'Ultimate Frisbee', 'Urban Exploration', 'Video Games', 'Violin', 'Volunteer', 'Walking',
    'Warhammer', 'Watching sporting events', 'Weather Watcher', 'Weightlifting', 'Windsurfing', 'Wine Making', 'Wingsuit Flying', 'Woodworking', 'Working In A Food Pantry', 'Working on cars', 'World Record Breaking',
    'Wrestling', 'Writing', 'Writing Music', 'Writing Songs', 'Yoga', 'YoYo', 'Ziplining', 'Zumba');

function get_random($list, $number = null) {

    if ($number == null) $number = 1;

    $item_list = array();
    $i = 0;
    while ($i < $number) {
        $item = $list[rand( 0 , count($list) - 1 )];
        if (!in_array($item, $item_list)) {
            $item_list[] = $item;
            $i++;
        }
    }
    return $item_list;

}

// Find a randomDate between $start_date and $end_date
function get_random_date($start_date, $end_date)
{
    // timestamp
    $min = strtotime($start_date);
    $max = strtotime($end_date);

    // Generate random number using above bounds
    $val = rand($min, $max);

    // Convert back to desired date format
    return date('Y-m-d', $val);
}

//echo '<pre>';
//$sex = rand(0,1);
//echo $sex ? 'male' : 'female'; echo '<br>';
//
//echo $first_name = get_random(($sex ? $male_first_names : $female_first_names)); echo '<br>';
//echo $last_name = get_random($last_names); echo '<br>';
//echo $DOB = get_random_date('01-04-1956', '01-04-1998'); echo '<br>';
//
//echo $email = $first_name.'.'.$last_name.substr($DOB, 2, 4).'@gmail.com';  echo '<br>';
//echo $password = '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8'; // password
//    echo '<br>';
//echo $role_id = 4;   // paid user
//    echo '<br>';
//
//
//echo $min = max(rand((2016 - substr($DOB, 0, 4)) - 10, (2016 - substr($DOB, 0, 4))), 18); echo '<br>';
//echo $max = rand((2016 - substr($DOB, 0, 4)), (2016 - substr($DOB, 0, 4)) + 10); echo '<br>';
//
//$likes = get_random($interests, rand( 0 , 10 ));
//$dislikes = get_random($interests, rand( 0 , 8 ));
//var_dump($likes);
//var_dump($dislikes);
//echo '</pre>';


$i = 0;
while ($i < 1) {
    $i++;

    $sex = rand(0,1);
    echo $sex ? 'male' : 'female';

    echo $first_name = get_random(($sex ? $male_first_names : $female_first_names))[0];
    echo $last_name = get_random($last_names)[0];
    echo $DOB = get_random_date('01-04-1956', '01-04-1998');

    echo $email = $first_name.'.'.$last_name.substr($DOB, 2, 4).'@gmail.com';
    echo $password = '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8'; // password
    echo $role_id = 4;   // paid user


    echo $min = max(rand((2016 - substr($DOB, 0, 4)) - 10, (2016 - substr($DOB, 0, 4))), 18);
    echo $max = rand((2016 - substr($DOB, 0, 4)), (2016 - substr($DOB, 0, 4)) + 10);

    $likes = get_random($interests, rand( 0 , 10 ));
    $dislikes = get_random($interests, rand( 0 , 8 ));


    $prepared = $db->prepare("
                INSERT INTO users (email, password, first_name, last_name, role_id)
                VALUES (?, ?, ?, ?, ?)
            ");

    $prepared->bind_param('ssssi', $email, $password, $first_name, $last_name, $role_id); //s - string

    if (!$prepared->execute()) continue;


    // Create a profile for the user using their assigned user_id
    $user_id = $prepared->insert_id;
    $looking_for = !$sex;

    $prepared = $db->prepare("
                INSERT INTO profiles (user_id, DOB, sex, date_time_updated, looking_for, min_age, max_age)
                VALUES (?, ?, ?, NOW(), ?, ?, ?)
            ");

    $prepared->bind_param('isiiii', $user_id, $DOB, $sex, $looking_for, $min, $max); //s - string

    if (!$prepared->execute()) continue;

    // Add likes/dislikes
    foreach ($likes as $like) {
        $likes_bool = true;
        $content = $like;

        $prepared = $db->prepare("
            CALL add_interest( ?, ?, ? );
        ");

        $prepared->bind_param('iis', $user_id, $likes_bool, $content);

        if (!$prepared->execute()) continue;
    }

    foreach ($dislikes as $dislike) {
        $likes_bool = false;
        $content = $dislike;

        $prepared = $db->prepare("
            CALL add_interest( ?, ?, ? );
        ");

        $prepared->bind_param('iis', $user_id, $likes_bool, $content);

        if (!$prepared->execute()) continue;
    }

}






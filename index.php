<?php
//class loading
require_once('./autoloader.php');


session_start();



$connectedUsr=null;



if (isset($_SESSION['id']) && isset($_SESSION['email'])){
        $connectedUsr=user::loadById($_SESSION['id']);
}



if ($connectedUsr !=null) {
    if ($_SERVER['REQUESTED_METHOD'] === 'POST') {
        if (!empty ($_POST['tweet'])) {
            $tweet= new tweet ();
            $tweet-> setText($_POST['tweet']);
            $tweet->setUserId ($_SESSION['id']);
            $tweet->save();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>    
    <title> Twitter Main Page</title>
    
    
    <link rel="stylesheet" href="css/style.css" type="text/css">
    </head>
    <body>  
        <h1> Welcome to our brand new Twitter. Hope you will like it! </h1>
        <h3> Here are all our tweets</h3>
        <?php
            $allTweets=tweet::loadAll();
                foreach ($allTweets as $tweet){
                    $user=user::loadById($tweet->getUserId());
                    echo "<tr>"
                        ."<th><a href='pages/user.php?id=".$user->getId()."'>". $user->getUsername(). "</a></th>"
                        ."<th>". $user->getEmail()."</th>"
                        ."</tr>";

                    echo "<tr>"
                    ."<th><a href='pages/twet.php?id=". $tweet->getId()."'>". $tweet->getText()."</a></th>"
                    ."<td>". $tweet->getCreationDate()."</td>".
                    "</tr>";
                }


             ?>       
    
    </body>
</html>
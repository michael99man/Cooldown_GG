<?php
ini_set('default_charset', 'utf-8');
header('Content-type: text/html; charset=utf-8');
ini_set('display_errors', 'On');
error_reporting(E_ALL);
include "api_functions.php";
$content = "<p>Nothing searched yet.</p><form action = 'cooldown.php' id='summonerForm' method='get'> 
                Summoner ID: <input type='text' name='summName' id='summName'/> 
                <input type='submit' value='Search'/> 
            </form>";
if(isset($_GET["summName"])) {  
    $name = $_GET["summName"];
    $id = getID($name);

    $players = getGame($id);
    if(is_array($players)){
        //separate players into two team lists
        $team1 = array();
        $team2 = array();
        foreach($players as $p){
            if($p->team == 100){
                $team1[] = $p;
            } else if($p->team == 200){
                $team2[] = $p;
            } else {
                print($p->name);
            }
        }


        $content = '<tr class="champ-row blue-border">';
        $i = 1;
        foreach($team1 as $p){
            $content .= add($p,$i);
            $i++;
        }

        $content .= '</tr><tr class="champ-row red-border">';
        foreach($team2 as $p){
            $content .= add($p,$i);
            $i++;
        }
        $content .= '</tr>';
    } else {
        //Error handling!
        $content .= "<p>Error: {$players}</p>";
    }
}
?>

<!DOCTYPE HTML>
<!--
Hologram by Pixelarity
pixelarity.com @pixelarity
License: pixelarity.com/license
-->
<html>
    <head>
        <title>Cooldown.gg</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
        <link rel="stylesheet" href="assets/css/main.css" />
        <!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
        <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
        <link rel="stylesheet" href="assets/css/style.css" />
        <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="assets/css/hover.css" media="all">
        <link rel="stylesheet" href="assets/css/bootstrap.css">
        <script type="text/javascript" src="assets/js/bootstrap.js"></script>
        <script type="text/javascript" src="assets/js/resize.js"></script>
    </head>
    <body class="landing">
        <!-- Header -->
        <!--
<header id="header">
<a href="#menu">Menu</a>
<a href="#">Log In</a>
<a href="#" class="button">Sign Up</a>
</header>

<nav id="menu">
<ul class="links">
<li><a href="index.html">Home</a></li>
<li><a href="generic.html">Generic</a></li>
<li><a href="elements.html">Elements</a></li>
</ul>
<ul class="actions vertical">
<li><a href="#" class="button special fit">Log In</a></li>
<li><a href="#" class="button fit">Sign Up</a></li>
</ul>
</nav>
-->

        <!-- Main -->
        <section id="main" class="wrapper style1">
            <div class="inner">

                <header class="major special">
                    <h1>Cooldowns</h1>
                </header>

                <div class="content">


                    <table class="champ-table">
                        <!-- each champ column will contain 4 horizontal ability_rows -->
                        <?php echo $content ?>
                    </table>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <?php include "footer.php"; ?>


        <!-- Scripts -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/jquery.scrolly.min.js"></script>
        <script src="assets/js/jquery.scrollex.min.js"></script>
        <script src="assets/js/skel.min.js"></script>
        <script src="assets/js/util.js"></script>
        <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
        <script src="assets/js/main.js"></script>

    </body>
</html>
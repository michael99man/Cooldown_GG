<?php
    ini_set('default_charset', 'utf-8');
    header('Content-type: text/html; charset=utf-8');
    ini_set('display_errors', 'On');
    error_reporting(E_ALL);
    include "api_functions.php";
    $content = "<p>Nothing searched yet.</p>";
    if(isset($_GET["summName"])) {  
        $name = $_GET["summName"];
        $id = getID($name);
        $players = getGame($id);
        
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
        
    
        $content = '<div class= "team-container-1">
        <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading blue">
                        <div class="panel-title">Blue Team</div>
                    </div>
                </div>
            </div>';
        $i = 1;
        foreach($team1 as $p){
            $content .= add($p,$i);
            $i++;
        }
        
        $content .= '</div><div class= "team-container-2">
        <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading purple">
                        <div class="panel-title">Purple Team</div>
                    </div>
                </div>
            </div>';
        foreach($team2 as $p){
            $content .= add($p,$i);
            $i++;
        }
        $content .= '</div>';
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Cooldown.gg</title>
        <meta name="keywords" content="">
        <meta name="description" content="">
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="stylesheet" href="assets/css/bootstrap.css">
        <link rel="stylesheet" href="assets/css/style.css">
        
        <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="assets/js/main.js"></script>
        <script type="text/javascript" src="assets/js/bootstrap.js"></script>
    </head>
    
    <body>
        <div class="wrapper">
            <!-- can redirect here by sending the GET to a new page-->
            <form action = "index.php" id="summonerForm" method="get"> 
                Summoner ID: <input type="text" name="summName" id="summName"/> 
                <input type="submit" value="Search"/> 
            </form>
            
 
            <?php echo $content ?>
            <!--
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <a data-toggle="collapse" href="#collapse1">Show abilities</a>
                        </div>
                    </div>
                </div>
            </div>
            <div id="collapse1" class="panel-collapse collapse">
                <ul class="list-group">
                    <li class="list-group-item" id="champ1Q">Q:</li>
                    <li class="list-group-item" id="champ1W">W:</li>
                    <li class="list-group-item" id="champ1E">E:</li>
                    <li class="list-group-item" id="champ1R">R:</li>
                </ul>
            </div>
            -->

        </div>
    </body>

</html>
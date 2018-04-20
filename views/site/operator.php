<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use app\models\User;
use app\models\WindowOperator;
use app\models\Window;
use yii\widgets\ActiveForm;

$this->title = 'Operator';
$operator = User::findOne(Yii::$app->user->getId());

echo "<p>Здравствуйте, " . $operator->full_name . "</p>";

$window_operator = $operator->getActiveWindowOperator();
function form_exit_window()
{
    echo "<form id='exit-window' action ='/site/exit-window'  method='post'>
    <input type='hidden' name='_csrf' value='" . Yii::$app->request->getCsrfToken() . "'>
    <p><input type='submit' name='submit' value='Выполнить талон и выйти'></p>
    </form>";
}

if($window_operator){
    $token = Yii::$app->request->getCsrfToken();
    $winoper_id = $window_operator->id;
    echo Window::findOne($window_operator->window_id)->name;
    form_exit_window();

    echo '<div id="ticket"></div>
    <div id="monitor"></div>
        <script>
        var winoper_id = ' . $winoper_id . ';
        window.onload = function() {
            var status = document.querySelector("#monitor");
            var your_ticket = document.querySelector("#ticket");

            ws = new WebSocket("ws://localhost:8008/");
            ws.onopen = function (evt) {
                ws.send("update");
            };
            ws.onmessage = function (evt) {
                if (evt.data != "") {
                    var tickets = JSON.parse(evt.data);
                    var table = "";
                    your_ticket.innerHTML = "Ожидайте талон";
                    for(var i = 0; i < tickets.length; i++){
                        
                        if(tickets[i]["window_oper_id"] == winoper_id){                          
                            your_ticket.innerHTML = "<p>Ваш талон: талон номер " + tickets[i]["id"] + "<p>";
                            your_ticket.innerHTML += "<form id=\'complete-ticket\' action =\'/site/complete-ticket\'  method=\'post\'>" + 
                            "<input type=\'hidden\' name=\'_csrf\' value=\''. $token .'\'>" +
                            "<p><input type=\'submit\' name=\'submit\' value=\'Талон выполнен\'></p>";
                        }                     
                        table += "<h1>" + tickets[i]["id"] + " -> " + tickets[i]["window"] +"</h1>";
                    }
                    status.innerHTML = table;
                }
            };
        }

    </script>';
}

else {
    $windows = Window::freeWindows();
    echo '<form id="enter-window" action ="/site/enter-window"  method="post">';
    echo '<input type="hidden" name="_csrf" value="' . Yii::$app->request->getCsrfToken() . '" />';
    $windows = Window::freeWindows();
    echo '<select name="window">';
    foreach ($windows as $window) {
        echo '<option value="' . $window->id . '">' . $window->name . '</option>';
    }
    echo '</select></p>';
    echo '<p><input type="submit" name="submit" value="Войти"></p>';
    echo '</form>';
}
?>
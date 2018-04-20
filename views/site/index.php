<?php

/* @var $this yii\web\View */

$this->title = 'Monitor';

//?>
<div >
    <div id="monitor"></div>
    <script>
        window.onload = function() {
            var status = document.querySelector("#monitor");
            ws = new WebSocket("ws://localhost:8008/");
            ws.onopen = function (evt) {
                ws.send("update");
            };
            ws.onmessage = function (evt) {
                if (evt.data != "") {
                    var tickets = JSON.parse(evt.data);
                    var table = "";
                    for(var i = 0; i < tickets.length; i++){
                        if (tickets[i]["window"]) {
                            table += "<h1>" + tickets[i]["id"] + " -> " + tickets[i]["window"] + "</h1>";
                        }
                    }
                    status.innerHTML = table;
                }
            };
        }

    </script>

</div>

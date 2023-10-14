<?php 
    include('inc/config.php');
    // include('function/login.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta name="description" content="Cuba admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Cuba admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="img/2021logo.jpg" type="image/x-icon"> -->
    <!-- <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon"> -->
    <title>Bingo</title>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  </head>
  <body class="box-layout">
    <style>
        .center {
            margin: 0;
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        
        td:hover {
            background-color: #f5f5f5;
            cursor: pointer;
        }

        .strikethrough {
            text-decoration: line-through;
            color: #ccc
        }
    </style>
    
    <div class="center">
        <?php
            // $_SESSION['GAMEID'] = 0;
        ?>
        <div class="row">
            <div class="col-md-12 col-sm-12 font-light f-30 text-center" style="padding: 20px">
                BINGO by fayex 
                <!-- <?= $_SESSION['GAMEID'] != 0 ? 'game ongoing'. $_SESSION['GAMEID'] : 'no game';?> -->
            </div>
        </div>
        <div class="row" id="bingo_button">
            <div class="col-md-12 col-sm-12 font-light f-30 text-center">
                <button class="btn btn-lg btn-success" id="btn_play">
                    Play Bingo
                </button>

                <button class="btn btn-lg btn-info" id="btn_call">
                    Call
                </button>

                <button class="btn btn-lg btn-danger pull-right" id="btn_stop">
                    Stop Game
                </button>

                <!-- <a class="btn btn-lg btn-danger pull-right" href="stop.php" id="btn_stop">Stop Game</a> -->
            </div>
        </div>
        <input type="text" value="" id="game_id" hidden>
        <hr>
        <?php
            // if($_SESSION['GAMEID'] != 0){
        ?>

            <div class="row" id="bingo_card_div">
                <div class="col-md-12 col-sm-12 font-light f-30 text-center">
                    <table class="table table-bordered h2">
                        <thead>
                            <tr>
                                <th class="text-center">B</th>
                                <th class="text-center">I</th>
                                <th class="text-center">N</th>
                                <th class="text-center">G</th>
                                <th class="text-center">O</th>
                            </tr>
                        </thead>
                        <tbody id="bingo_card">
                            <!-- <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
            </div>
        <?php
            // }
        ?>
        
        
    </div>
    

    <script>
        $('#bingo_card_div').hide();
        $('#btn_stop').hide();
        
        if(localStorage.getItem("game") == 0){
            localStorage.setItem("letterB", []);
            localStorage.setItem("letterI", []);
            localStorage.setItem("letterN", []);
            localStorage.setItem("letterG", []);
            localStorage.setItem("letterO", []);
        }else{
            $('#btn_play').hide();
            $('#bingo_card_div').show();
            $('#btn_stop').show();
            $('#btn_call').hide();

            $('#game_id').val(localStorage.getItem("game"));
            getPreSetBingoCard();
        }


        $('#btn_play').click(()=>{
            $('#btn_play').hide();
            $('#bingo_card_div').show();
            $('#btn_stop').show();
            $('#btn_call').hide();


            $.ajax({
                url: 'ajax/ajax_bingo.php', 
                data: {
                    flag: 'playBingo', 
                }, 
                complete: function(res){
                    // $('#text_call').text(res.responseText);
                    // console.log(res);
                    getBingoCard();
                    localStorage.setItem("game", res.responseText);
                    $('#game_id').val(localStorage.getItem("game") + '-');
                }
            });
        });

        $('#btn_stop').click(()=>{
            console.log(localStorage.getItem("game"));
            $.ajax({
                url: 'ajax/ajax_bingo.php', 
                data: {
                    flag: 'stopGame', 
                    gameId: localStorage.getItem("game")
                }, 
                complete: function(res){
                    // $('#text_call').text(res.responseText);
                    localStorage.setItem("game", 0);
                }
            });
            location.reload();
        });

        function getNumbers(min, max, count) {
            if (count > max - min + 1) {
                return null;
            }

            var uniqueNumbers = [];
            // do {
            for (let i = 0; i < count; i++) {
                var randomNumber = Math.round(Math.random() * (max - min + 1) + min);
                if(!uniqueNumbers.includes(randomNumber)){
                    uniqueNumbers.push(randomNumber);
                }else count++;
            }
            
            return uniqueNumbers;
        }
        
        function getBingoCard(){
            const numberTable = document.getElementById("bingo_card");
            
            var letterBArr = [];
            var letterIArr = [];
            var letterNArr = [];
            var letterGArr = [];
            var letterOArr = [];

            for (let i = 0; i < 5; i++) {
                var letterB = getNumbers(1, 25, 5)[i];
                var letterI = getNumbers(26, 40, 5)[i];
                var letterN = getNumbers(41, 55, 5)[i];
                var letterG = getNumbers(56, 70, 5)[i];
                var letterO = getNumbers(71, 85, 5)[i];

                const row = document.createElement("tr");

                const cellB = document.createElement("td");
                cellB.setAttribute("id", `numB_${i}`);
                cellB.textContent = letterB;
                cellB.onclick = function() {
                    cellB.classList.add('strikethrough');
                    // bingoChecker(`[${i},0]`);
                    checkForBingoWinRow(bingoChecker(`[${i},0]`));
                    checkForBingoColumn(bingoChecker(`[${i},0]`), 0);
                    checkForBingoDiag(`[${i},0]`);
                };
                row.appendChild(cellB);

                const cellI = document.createElement("td");
                cellI.setAttribute("id", `numI_${i}`);
                cellI.textContent = letterI;
                cellI.onclick = function() {
                    cellI.classList.add('strikethrough');
                    // bingoChecker(`[${i},1]`);
                    checkForBingoWinRow(bingoChecker(`[${i},1]`));
                    checkForBingoColumn(bingoChecker(`[${i},1]`), 1);
                    checkForBingoDiag(`[${i},1]`);
                };
                row.appendChild(cellI);

                const cellN = document.createElement("td");
                cellN.setAttribute("id", `numN_${i}`);
                cellN.textContent = letterN;
                cellN.onclick = function() {
                    cellN.classList.add('strikethrough');
                    // bingoChecker(`[${i},2]`);
                    checkForBingoWinRow(bingoChecker(`[${i},2]`));
                    checkForBingoColumn(bingoChecker(`[${i},2]`), 2);
                    checkForBingoDiag(`[${i},2]`);
                };
                row.appendChild(cellN);

                const cellG = document.createElement("td");
                cellG.setAttribute("id", `numG_${i}`);
                cellG.textContent = letterG;
                cellG.onclick = function() {
                    cellG.classList.add('strikethrough');
                    // bingoChecker(`[${i},3]`);
                    checkForBingoWinRow(bingoChecker(`[${i},3]`));
                    checkForBingoColumn(bingoChecker(`[${i},3]`), 3);
                    checkForBingoDiag(`[${i},3]`);
                };
                row.appendChild(cellG);

                const cellO = document.createElement("td");
                cellO.setAttribute("id", `numO_${i}`);
                cellO.textContent = letterO;
                cellO.onclick = function() {
                    cellO.classList.add('strikethrough');
                    // bingoChecker(`[${i},4]`);
                    checkForBingoWinRow(bingoChecker(`[${i},4]`));
                    checkForBingoColumn(bingoChecker(`[${i},4]`), 4);
                    checkForBingoDiag(`[${i},4]`);
                };
                row.appendChild(cellO);

                letterBArr.push(letterB);
                letterIArr.push(letterI);
                letterNArr.push(letterN);
                letterGArr.push(letterG);
                letterOArr.push(letterO);

                
                numberTable.appendChild(row);
            }

            

            localStorage.setItem("letterB", JSON.stringify(letterBArr));
            localStorage.setItem("letterI", JSON.stringify(letterIArr));
            localStorage.setItem("letterN", JSON.stringify(letterNArr));
            localStorage.setItem("letterG", JSON.stringify(letterGArr));
            localStorage.setItem("letterO", JSON.stringify(letterOArr));

        }

        function getPreSetBingoCard(){
            console.log(JSON.parse(localStorage.getItem("letterB"))[0]);
            const numberTable = document.getElementById("bingo_card");

            for (let i = 0; i < 5; i++) {
                var letterB = JSON.parse(localStorage.getItem("letterB"))[i];
                var letterI = JSON.parse(localStorage.getItem("letterI"))[i];
                var letterN = JSON.parse(localStorage.getItem("letterN"))[i];
                var letterG = JSON.parse(localStorage.getItem("letterG"))[i];
                var letterO = JSON.parse(localStorage.getItem("letterO"))[i];

                const row = document.createElement("tr");

                const cellB = document.createElement("td");
                cellB.setAttribute("id", `numB_${i}`);
                cellB.setAttribute("indexes", `[${i},0]`);
                cellB.textContent = letterB;
                cellB.onclick = function() {
                    cellB.classList.add('strikethrough');
                    // bingoChecker(`[${i},0]`);
                    checkForBingoWinRow(bingoChecker(`[${i},0]`));
                    checkForBingoColumn(bingoChecker(`[${i},0]`), 0);
                    checkForBingoDiag(`[${i},0]`);
                };
                row.appendChild(cellB);

                const cellI = document.createElement("td");
                cellI.setAttribute("id", `numI_${i}`);
                cellI.setAttribute("indexes", `[${i},1]`);
                cellI.textContent = letterI;
                cellI.onclick = function() {
                    cellI.classList.add('strikethrough');
                    // bingoChecker(`[${i},1]`);
                    checkForBingoWinRow(bingoChecker(`[${i},1]`));
                    checkForBingoColumn(bingoChecker(`[${i},1]`), 1);
                    checkForBingoDiag(`[${i},1]`);
                };
                row.appendChild(cellI);

                const cellN = document.createElement("td");
                cellN.setAttribute("id", `numN_${i}`);
                cellN.setAttribute("indexes", `[${i},2]`);
                cellN.textContent = letterN;
                cellN.onclick = function() {
                    cellN.classList.add('strikethrough');
                    // bingoChecker(`[${i},2]`);
                    checkForBingoWinRow(bingoChecker(`[${i},2]`));
                    checkForBingoColumn(bingoChecker(`[${i},2]`), 2);
                    checkForBingoDiag(`[${i},2]`);
                };
                row.appendChild(cellN);

                const cellG = document.createElement("td");
                cellG.setAttribute("id", `numG_${i}`);
                cellG.setAttribute("indexes", `[${i},3]`);
                cellG.textContent = letterG;
                cellG.onclick = function() {
                    cellG.classList.add('strikethrough');
                    // bingoChecker(`[${i},3]`);
                    checkForBingoWinRow(bingoChecker(`[${i},3]`));
                    checkForBingoColumn(bingoChecker(`[${i},3]`), 3);
                    checkForBingoDiag(`[${i},3]`);
                };
                row.appendChild(cellG);

                const cellO = document.createElement("td");
                cellO.setAttribute("id", `numO_${i}`);
                cellO.setAttribute("indexes", `[${i},4]`);
                cellO.textContent = letterO;
                cellO.onclick = function() {
                    cellO.classList.add('strikethrough');
                    // bingoChecker(`[${i},4]`);
                    checkForBingoWinRow(bingoChecker(`[${i},4]`));
                    checkForBingoColumn(bingoChecker(`[${i},4]`), 4);
                    checkForBingoDiag(`[${i},4]`);
                };
                row.appendChild(cellO);

                
                numberTable.appendChild(row);
            }

            
        }

        var checked =  [
                [false, false, false, false, false],
                [false, false, false, false, false],
                [false, false, false, false, false],
                [false, false, false, false, false],
                [false, false, false, false, false]
            ];

        function bingoChecker(indexes){
            // console.log(JSON.parse(indexes))
            var bIndex = JSON.parse(indexes);
            // var checked =  [
            //     [false, false, false, false, false],
            //     [false, false, false, false, false],
            //     [false, false, false, false, false],
            //     [false, false, false, false, false],
            //     [false, false, false, false, false]
            // ];

            checked[bIndex[0]][bIndex[1]] = true;
            console.log(checked); 
            return checked;
        }

        function checkForBingoWinRow(card) {
            for (let i = 0; i < card.length; i++) {
                let consecutiveStrikes = 0;
                for (let j = 0; j < card[i].length; j++) {
                if (card[i][j]) {
                    consecutiveStrikes++;
                    if (consecutiveStrikes === 5) {
                        // console.log('win row', consecutiveStrikes);
                        alert('You Won!');
                        return true; // Bingo win!
                    }
                } else {
                    // console.log(i, j);
                    consecutiveStrikes = 0;
                }
                }
            }

            return false; // No win
        }

        function checkForBingoColumn(card, columnIndex) {
            let consecutiveStrikes = 0;

            for (let i = 0; i < card.length; i++) {
                if (card[i][columnIndex]) {
                consecutiveStrikes++;
                if (consecutiveStrikes === 5) {
                    // console.log('win col', consecutiveStrikes);
                    alert('You Won Col!');
                    return true; // Bingo win in the specified column
                }
                } else {
                consecutiveStrikes = 0;
                }
            }

            return false; // No win in the specified column
        }

        var arrDiag = ["04", "13", "22", "31", "40"];
        var arrDiagChkr = [];
        let consecutiveStrikesDiag = 0;
        function checkForBingoDiag(tblCell){
            var tblVal = JSON.parse(tblCell);
            // if(tblVal[0] == tblVal[1] || arrDiag.includes(`${tblVal[0]}${tblVal[1]}`)){
            //     consecutiveStrikesDiag++;
            // }

            if(`${tblVal[0]}${tblVal[1]}` == "22"){
                arrDiagChkr.push(`${tblVal[0]}${tblVal[1]}`);
                consecutiveStrikesDiag++;
            }else{
                if(arrDiag.includes(`${tblVal[0]}${tblVal[1]}`)){
                    // consecutiveStrikesDiag++;
                    arrDiagChkr.push(`${tblVal[0]}${tblVal[1]}`);
                }else{
                    if(tblVal[0] == tblVal[1]) consecutiveStrikesDiag++;
                    console.log(consecutiveStrikesDiag);
                }
            }

            if (consecutiveStrikesDiag === 5 || arrDiagChkr.length == 5) {
                // console.log('win row', consecutiveStrikesDiag);
                alert('You Won Diag!');
                return true; // Bingo win!
            }
                
            
            return false;
        }
        
        
    </script>
  </body>
</html>

        
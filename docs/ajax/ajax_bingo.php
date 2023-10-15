<?php
include_once('../inc/config.php');

    function func_playBingo(){
        $con = getConnection();
            $insert = $con->query('INSERT INTO tbl_bingo_game (game_name, game_status) VALUES("Bingo Game '.date('F j, Y H:i:s').'", 1);');

            // $_SESSION['GAMEID'] = $con->insert_id;
            $game_id = $con->insert_id;

        $con->close();
        return $game_id;
    }

    function func_saveNumber($gameId, $calledNumber){
        $con = getConnection();
            $insert = $con->query('INSERT INTO tbl_bingo_number (game_id, bingo_number) VALUES('.$gameId.', '.$calledNumber.');');

        $con->close();
        return $insert;
    }

    function func_stopGame($gameId){
        // $con = getConnection();
        //     $_SESSION['GAMEID'] = 0;
        // $con->close();
        // header('location:index.php');
        $con = getConnection();
            $con->query('UPDATE tbl_bingo_game SET game_status = 0 WHERE game_id = '.$gameId).';';

            // $_SESSION['GAMEID'] = $con->insert_id;

        $con->close();
        // return $con->insert_id;
    }

    function func_getOngoingGame($gameId){
        $con = getConnection();
            $arrNos = array();
            $query = 'SELECT * FROM tbl_bingo_number WHERE game_id = '.$gameId.';';
            $select = $con->query($query);
            while($row = $select->fetch_object()){
                array_push($arrNos, (int)$row->bingo_number);
            }

            // $_SESSION['GAMEID'] = $con->insert_id;

        $con->close();
        return $arrNos;
    }

    $flag = $_REQUEST['flag'];

    if($flag == 'playBingo'){

        echo func_playBingo();
    }
    else if($flag == 'saveNumber'){
        $gameId = $_REQUEST['gameId'];
        $calledNumber = $_REQUEST['calledNumber'];

        echo func_saveNumber($gameId, $calledNumber);
    }
    else if($flag == 'stopGame'){
        $gameId = $_REQUEST['gameId'];

        echo func_stopGame($gameId);
    }
    else if($flag == 'getOngoingGame'){
        $gameId = $_REQUEST['gameId'];

        echo json_encode(func_getOngoingGame($gameId));
    }
?>
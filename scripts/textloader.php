<?php /* MIT License

Copyright (c) 2021 Todd Perry

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE. */

include('../serverconfig.php');
include($GLOBALS['FATEPATH'] . '/fate.php');
//ini_set('memory_limit', '2GB');

set_time_limit(0);

$GLOBALS['DBVERBOSE'] = false;

define('MIN_LINE_LEN', 40);
define('MIN_TOK_LEN', 5);

define('REPORT_MOD', 1000);

define('CMD_CLEAR_ALL', 1);
define('CMD_LOAD_ALL', 2);
define ('CMD_GET_ALL_TEXT_FILES', 3);

//define default num of max textfiles to load at a time
//to set the default num of max textfiles to load at a time, set an the api field 'ntl'
define('MAX_TEXT_LOAD',1);



//get script command
$CMD = 0;
if(isset($_POST['cmd'])){
    $CMD = $_POST['cmd'];
}

switch ($CMD){
    case CMD_CLEAR_ALL:
        clearAll();
        break;
    case CMD_LOAD_ALL:
        loadAll();
        break;
    case CMD_GET_ALL_TEXT_FILES:
        getAllTextFiles();
        break;
    default:
        echo "THE SCRIPT COMMAND ".$CMD. " IS NOT RECOGNIZED";
}

function loadAll(){
    //variables for time reporting
    $dirScanningTime =0;
    $filteringOutLoadedTime =0;
    $insertingIntoBooksTime =0;
    $clearingInterupptedToksChestsTime =0;
    $insertingIntoToksChestsTime = 0; //

    //echo "LOADING  TEXT FILES \r\n";
    $numOfMaxTextFileToLoad = MAX_TEXT_LOAD;
    if(isset($_POST['ntl'])){
        $ntl = intval($_POST['ntl']);
        if($ntl > 0){
            $numOfMaxTextFileToLoad = $ntl;
        }
    }

    $flag = 0;
    $starttime = time();

    $datapath = $GLOBALS['FATEPATH'] . '/data/fatetexts/';
    //read poetry
    $poetry_files = scandir($datapath.'poetry');
    $prose_files = scandir($datapath.'prose');

    //associative array stores both the poetry and prose; key = filename; value = textfiletype;i.e poetry/prose
    $textFiles = array();
    $unLoadedTextFiles  = array();

    //this associative array holds the final list of textfiles that are ready to be loaded
    //keys are the bookid, the values are 'textfiletype/filename' which is partial path; e.g 'poetry/filename'
    $textFilesTobeLoaded = array();


    //get all poetry in the $datapath
    $dirScanningTime = time();
    foreach ($poetry_files as $key => $file) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $file_name_no_ext = pathinfo($file, PATHINFO_FILENAME);
        if ($ext == "txt") {
            $textFiles[$file_name_no_ext] = "poetry";
        }
    }
    //get all prose in the $datapath
    foreach ($prose_files as $key => $file) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $file_name_no_ext = pathinfo($file, PATHINFO_FILENAME);
        if ($ext == "txt") {
            $textFiles[$file_name_no_ext] = "prose";
        }
    }
    $dirScanningTime = time() - $dirScanningTime;

    //filter out textfiles already loaded
    $filteringOutLoadedTime = time();
    $loadedTextFiles = mod_get_loadedBooks_title();
    foreach ($textFiles as $fileName => $textFileType){
        if(!in_array($fileName, $loadedTextFiles)){
            $unLoadedTextFiles[$fileName] = $textFileType;
        }
    }
    $filteringOutLoadedTime = time() - $filteringOutLoadedTime;

    //insert unloaded textfiles into books table that are not  inserted
    $insertingIntoBooksTime = time();
    $allTextFilesInBooksTable = mod_get_allbooks_title(); //
    foreach ($unLoadedTextFiles as $unLoadedTextFileName => $textFileType){
        if(!in_array($unLoadedTextFileName, $allTextFilesInBooksTable)){
            $author ="";
            $type = $textFileType;
            $txtFileDatapath = $datapath . '/' . $textFileType . '/' . $unLoadedTextFileName.'.txt';
            //insert into books table
            $sql = 'INSERT INTO books (titlestr,authorstr,datapath,type)';
            $sql .= ' VALUES ( %s, %s, %s , %s)';
            queryf($sql,  $unLoadedTextFileName, $author, $txtFileDatapath, $type);

        }
    }
    $insertingIntoBooksTime = time() - $insertingIntoBooksTime;


    //clear toks and chests entries of textfiles not completely loaded and
    //get texfiles ready tobe loaded - along with their id
    $clearingInterupptedToksChestsTime =  time();
    //use nested query for time efficiency
    $sql = 'DELETE FROM chests WHERE bookid IN ( SELECT bookid FROM books WHERE isLoaded = false)';
    if(!unsafe_query($sql)){
        $flag++;
    }
    $sql = 'DELETE FROM toks WHERE bookid IN ( SELECT bookid FROM books WHERE isLoaded = false)';
    if(!unsafe_query($sql)){
        $flag++;
    }

    $allTextFilesInBooksTable = mod_get_allbooks_title(); //
    $unLoadedTextFiles_keys = array_keys($unLoadedTextFiles); //which is filenames only
    foreach ($allTextFilesInBooksTable as $book_id => $bookTitle){
        if(in_array($bookTitle,$unLoadedTextFiles_keys)){
            $textFilesTobeLoaded[$book_id] = $unLoadedTextFiles[$bookTitle]."/".$bookTitle.".txt";
        }
    }

    $clearingInterupptedToksChestsTime =  time() - $clearingInterupptedToksChestsTime;

    //load the unloaded text files if no error happens
    $insertingIntoToksChestsTime = time();
    if($flag == 0){
        $numOfTextFilesLoaded = 0;
        foreach ($textFilesTobeLoaded as $book_id => $file_path) {
            $starttime_per_file = time();
            $text = file_get_contents($datapath . $file_path);

            //get the textfile type -- poetry or prose
            $textFileType = explode("/", $file_path)[0];


            //echo $file_path . ' len: ' . strlen($text);
            //echo "\n";

            $lines = preg_split('/(?<=[.?!])\s+(?=[a-z])/i', $text);
            $chests = array();

            $cleanchars = ' ~`#{}\!\"\$\%\&\'\(\)\,\-\.\/\:\;\<\=\>\?\@';
            $cleanchars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ\[\]\_';
            $cleanchars .= 'abcdefghijklmnopqrstuvwxyz0123456789';

            //new line characters
            $newLineChars = array("\r\n","\r","\n");

            $charcounts = array();
            $cclen = strlen($cleanchars);
            for ($i = 0; $i < $cclen; $i++) {
                $charcounts[$cleanchars[$i]] = true;
            }

            $i = 0;
            $prevline = '';
            $trip = array('', '', '');
            foreach ($lines as $line) {
                $linelen = strlen($line);
                if (strlen($linelen) < 1) {
                    util_except("found empty line at i = $i");
                }

                if ($line[0] == '_') {
                    echo 'Skipping: ' . $line . "\n";
                    continue;
                }

                $line = $prevline . ' ' . $line;
                $prevline = '';
                if ($linelen < MIN_LINE_LEN) {
                    $prevline = $line;
                    continue;
                }


                $cleanline = '';
                $linelen = strlen($line);
                for ($j = 0; $j < $linelen; $j++) {
                    //if the file type is prose replace the newline chars with spaces
                    if($textFileType == 'prose'){
                        str_replace($newLineChars," ",$line[$j]);
                    }
                    if (isset($charcounts[$line[$j]]) || in_array($line[$j],$newLineChars)) {
                        $cleanline .= $line[$j];
                    }
                }

                $chests [] = utf8_encode($cleanline);
                $i++;
            }

            util_assert($i == count($chests));
           // echo 'found ' . $i . ' chests' . "<br>\r\n";

            $sql = 'INSERT INTO chests (datastr, bookid)';
            $sql .= ' VALUES (%s, %d)';

            $toksarr = array();
            $i = 0;
            foreach ($chests as $datastr) {
                queryf($sql, $datastr, $book_id);
                $lid = last_insert_id();
                $i++;

                $toks = explode(" ", $datastr);
                foreach ($toks as $tok) {
                    $toklen = strlen($tok);

                    $trimtok = '';
                    $started = false;
                    for ($j = 0; $j < $toklen; $j++) {
                        if (ctype_alpha($tok[$j])) {
                            //accumulate characters until a non-alphabet char is seen
                            $trimtok .= $tok[$j];
                            $started = true;
                        } else {
                            if ($started) {
                                break;
                            }
                        }
                    }

                    $trimtok = strtolower($trimtok);
                    $trimtoklen = strlen($trimtok);
                    if ($trimtoklen >= MIN_TOK_LEN) {
                        if (!isset($toksarr[$trimtok])) {
                            $toksarr[$trimtok] = array();
                        }
                        $toksarr[$trimtok][$lid] = true;
                    }

                } //end foreach toks

                if ($i % REPORT_MOD == 0) {
                   // echo "inserted $i chests into the db <br>\r\n";
                }

            } //end foreach chests


            $sql = 'INSERT INTO toks (tokstr, chestidstr, bookid)';
            $sql .= ' VALUES (%s, %s, %d)';


            $i = 0;
            foreach ($toksarr as $tok => $lids) {
                $tripidstr = implode(' ', array_keys($lids));

                //TODO what's this?
                if ($tok == 'misunderstanding') {
                    continue;
                }

                queryf($sql, $tok, $tripidstr, $book_id);
                $i++;

                if ($i % REPORT_MOD == 0) {
                    //echo "inserted $i toks into the db<br>\r\n";
                }
            }

            //update the loaded textfile
            $loadStatus = "";
            if(count($chests) == 0){
                $sql = 'UPDATE books SET isLoaded = false WHERE bookid = %d';
                $res = queryf($sql,$book_id);
                if($res){
                    $loadStatus = "not loaded! \r\n<BR>";
                }
            }else{
                $sql = 'UPDATE books SET isLoaded = true WHERE bookid = %d';
                $res = queryf($sql,$book_id);
                if($res){
                    $loadStatus = "successfully loaded! \r\n<BR>";
                }
            }

            $elapsed_time_per_file = time() - $starttime_per_file;
 /*
            //print loading status per textfile
            echo "\r\n -----------------------------------------------------\r\n".
                "File Name: ". $file_path. "\r\n".
                "Number of characters: ". strlen($text). "\r\n".
                "Number of Chests: ". count($chests). "\r\n".
                "Number of tokens: ". count($toksarr). "\r\n".
                "Status: ". $loadStatus.
                "File Size: ".filesize($datapath . $file_path)." bytes "."\r\n".
                "Elapsed Time: ". $elapsed_time_per_file ." seconds\r\n".
                " ------------------------------------------------------\r\n";
*/

            $numOfTextFilesLoaded++;
            if($numOfTextFilesLoaded == $numOfMaxTextFileToLoad){
                break;
            }
        }
    }else{
        //echo "Error loading text files";
    }
    $insertingIntoToksChestsTime = time() - $insertingIntoToksChestsTime;

    $remainingUnloadedTextFiles = count($textFilesTobeLoaded) - $numOfMaxTextFileToLoad;

    //prepare response
    $allTextFilesInBooksTable = mod_get_allbooks_title();
    $response = array();
    if($flag == 0){
        $response['status'] = true;
        $response['unloadedTextFiles'] = $remainingUnloadedTextFiles;
        $response['allTextFiles'] = count($allTextFilesInBooksTable);
        $response['message'] = count($allTextFilesInBooksTable) - $remainingUnloadedTextFiles  ." of ". count($allTextFilesInBooksTable) ." text files are loaded!";
    }else{
        $response['status'] = false;
        $response['message'] = "Something went wrong please try loading again.";
    }

    //send response
    echo json_encode($response);

/*
    //print reports
    echo "\r\n-------------------------------------SUMMARY---------------------------\r\n";
    echo $numOfMaxTextFileToLoad. " text files are loaded.\r\n";
    if($remainingUnloadedTextFiles > 0){
        echo $remainingUnloadedTextFiles. " text files remain.\r\n".
            "Please run the text loading script again to load the remaining text files. \r\n".
            "RUN THE SCRIPT AGAIN!!!!!!\r\n";
    }else{
        echo "All TEXT FILES ARE LOADED! \r\n";
    }

    $elapsed = time() - $starttime;
    echo "DONE in $elapsed seconds\n\n";
    echo "\r\n--------------------------------------------------------------------\r\n";

    //print time report summary
    echo "\r\n-------------------------------------SUMMARY---------------------------\r\n";
    echo "Time to scan the datapath directory = ".$dirScanningTime." seconds \r\n";
    echo "Time to filter out loaded text files = ".$filteringOutLoadedTime." seconds \r\n";
    echo "Time to insert into books table = ".$insertingIntoBooksTime." seconds \r\n";
    echo "Time to clear the interuppted textfile's chests and toks = ".$clearingInterupptedToksChestsTime." seconds \r\n";
    echo "Time to insert into chests and toks table = ".$insertingIntoToksChestsTime." seconds \r\n";
    echo "\r\n--------------------------------------------------------------------\r\n";
*/

}
function clearAll(){
    $flag = 0;
    $sql = "TRUNCATE TABLE books";
    if(!queryf($sql)) $flag++;
    $sql = "TRUNCATE TABLE toks";
    if(!queryf($sql)) $flag++;
    $sql = "TRUNCATE TABLE chests";
    if(!queryf($sql)) $flag++;

    //$sql = "TRUNCATE TABLE gems";
    //if(!queryf($sql)) $flag++;

    if($flag == 0){
        $res["status"] = true;
        $res["message"] = "All loaded textfiles are cleared from the database!";
        echo json_encode($res);
    }else{
        $res["status"] = false;
        $res["message"] = "Error clearing loaded textfiles! ";
        echo json_encode($res);
    }

}

function getAllTextFiles(){
    $allTextFilesInBooksTable = mod_get_all_books(); //
    echo json_encode($allTextFilesInBooksTable);
}


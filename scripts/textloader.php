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
    default:
        echo "THE SCRIPT COMMAND IS NOT RECOGNIZED";
}

function loadAll(){
    echo "LOADING  TEXT FILES<BR>\r\n";
    $flag = 0;
    $starttime = time();

    $datapath = $GLOBALS['FATEPATH'] . '/data/fatetexts/';
    $files = scandir($datapath);

    $textFiles = array();
    $unLoadedTextFiles  = array();
    $textFilesTobeLoaded = array();


    //get all textfiles in the $datapath
    foreach ($files as $key => $file) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $file_name_no_ext = pathinfo($file, PATHINFO_FILENAME);
        if ($ext == "txt") {
            $textFiles[] = $file_name_no_ext;
        }
    }

    //filter out textfiles already loaded
    $loadedTextFiles = mod_get_loadedBooks_title();
    foreach ($textFiles as $textFile){
        if(!in_array($textFile, $loadedTextFiles)){
            $unLoadedTextFiles[] = $textFile;
        }
    }


    //insert unloaded textfiles into books table that are not already inserted
    $allTextFilesInBooksTable = mod_get_allbooks_title(); //
    foreach ($unLoadedTextFiles as $unLoadedTextFile){
        if(!in_array($unLoadedTextFile, $allTextFilesInBooksTable)){
            $author ="";
            $txtFileDatapath = $datapath . '/' . $unLoadedTextFile.'.txt';
            //insert into books table
            $sql = 'INSERT INTO books (titlestr,authorstr,datapath)';
            $sql .= ' VALUES ( %s, %s, %s)';
            queryf($sql,  $unLoadedTextFile, $author, $txtFileDatapath);

        }
    }


    //clear toks and chests entries of textfiles not completely loaded and
    //get texfiles ready tobe loaded - along with their id
    $allTextFilesInBooksTable = mod_get_allbooks_title(); //
    foreach ($allTextFilesInBooksTable as $book_id => $bookTitle){
        if(in_array($bookTitle,$unLoadedTextFiles)){
            $sql = 'DELETE FROM toks WHERE bookid ='.$book_id;
            if(!unsafe_query($sql)){
                $flag++;
            }
            $sql = 'DELETE FROM chests WHERE bookid ='.$book_id;
            if(!unsafe_query($sql)){
                $flag++;
            }

            $textFilesTobeLoaded[$book_id] = $bookTitle.".txt";
        }
    }


    //echo " LIST OF TEXT FILES READY TO BE LOADED <br>";
    //var_dump($textFilesTobeLoaded);

    //load the unloaded text files if no error happens
    if($flag == 0){
        foreach ($textFilesTobeLoaded as $book_id => $file_path) {
            $starttime_per_file = time();
            $text = file_get_contents($datapath . $file_path);

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
            //print loading status per textfile
            echo "<br> LOADING REPORT<br>\r\n ".
                "<br>\r\n ------------------------------------------------------<br>\r\n".
                "File Name: ". $file_path. "\r\n<br>".
                "Number of characters: ". strlen($text). "\r\n<br>".
                "Number of Chests: ". count($chests). "\r\n<br>".
                "Number of tokens: ". count($toksarr). "\r\n<br>".
                "Status: ". $loadStatus.
                "File Size: ".filesize($datapath . $file_path)." bytes "."\r\n<br>".
                "Elapsed Time: ". $elapsed_time_per_file ."\r\n<br>".
                " ------------------------------------------------------\r\n<br>";


        }
    }else{
        echo "Error loading text files";
    }

    $elapsed = time() - $starttime;
    echo "DONE in $elapsed seconds\n\n";

}

function clearAll(){
    $flag = 0;
    echo "clearing books,toks and chests...... <br> ";
    $sql = "TRUNCATE TABLE books";
    if(!queryf($sql)) $flag++;
    $sql = "TRUNCATE TABLE toks";
    if(!queryf($sql)) $flag++;
    $sql = "TRUNCATE TABLE chests";
    if(!queryf($sql)) $flag++;

    //$sql = "TRUNCATE TABLE gems";
    //if(!queryf($sql)) $flag++;

    if($flag == 0){
        echo "All loaded textfiles are cleared from the database ";
    }else{
        echo "Error clearing loaded textfiles ";
    }

}

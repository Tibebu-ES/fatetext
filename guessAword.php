<?php
define('CMD_CLEAR_ALL', 1);
define('CMD_LOAD_ALL', 2);
define('CMD_GET_ALL_TEXT_FILES', 3);
define('CMD_A_RANDOM_SENTENCE', 4);
define('CMD_GET_RECENT_HISTORY', 5);
define('CMD_ADD_CURRENT_GUESS', 6);
define('CMD_GET_GUESS', 7);
define('TEXTLOADER_URL', "http://www.questiontask.com/scripts/textloader.php");
//define('TEXTLOADER_URL', "http://localhost:8081/fatetext/scripts/textloader.php");
//define('TEXTLOADER_URL', "http://localhost/fatetext/scripts/textloader.php");
//Fate text model

?>

<html>

<head>
    <title>Guess A Word</title>

    <!-- Latest compiled and minified CSS -->
    <!-- Bootstrap v4.3.1 CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/textloader.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker3.standalone.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />


    <!-- jQuery library -->
    <script type="text/javascript" src="js/jquery-3.6.0.min.js"> </script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="js/moment.js"></script>


    <style>
        .overlay {
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 999;
            background: rgba(255, 255, 255, 0.8) url("loader.gif") center no-repeat;
        }

        /* Turn off scrollbar when body element has the loading class */
        body.loading {
            overflow: hidden;
        }

        /* Make spinner image visible when body element has the loading class */
        body.loading .overlay {
            display: block;
        }

        .ui-widget-header {
            background: #cedc98;
            border: 1px solid #DDDDDD;
            color: darkred;
            font-weight: bold;
        }

        .container {
            margin-top: 20px;
            margin-bottom: 30px;
        }

        #full-text-view {
            padding: 10px;
            white-space: pre-wrap;
            max-height: 300px;
            overflow-y: auto;
        }

        #full-text-view #senSpan {
            color: #ffc107;
            font-weight: bold;
        }

        #full-text-view #senSpan #wordSpan {
            text-decoration: underline;
        }

        #blankedWordSpan {
            color: #ffc107;
            font-weight: bold;
            text-decoration: underline;
        }
    </style>

</head>

<body>
    <div class="overlay"></div>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" onclick="restart()">Guess A Word</a>

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a href="#exampleModal" type="button" class="btn btn-outline-light" data-toggle="modal" d ata-target="#exampleModal" onclick="getRecentGuesses()">Past Games <i class="fa fa-history fa-"></i>
                    </a>
                </li>
            </ul>

        </div>
    </nav>

    <div class="container">


        <!-- Game History Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Past Games</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="d-flex p-2 bg-light">
                        <div class="form-check">
                            <label for="for_guess"><strong>Guess</strong></label>
                            <input id="for_guess" type="checkbox" class="form-control form-control-sm" name="for_guess">
                        </div>
                        <div class="form-check">
                            <label for="for_ans_word"><strong>Ans-Word</strong></label>
                            <input id="f_aword" type="checkbox" class="form-control form-control-sm" name="for_ans_word">
                        </div>
                        <div class="form-check">
                            <label for="for_question"><strong>Question</strong></label>
                            <input id="f_que" type="checkbox" class="form-control form-control-sm" name="for_question">
                        </div>
                        <div class="form-check">
                            <label for="for_ans_sen"><strong>Ans-Sen</strong></label>
                            <input type="checkbox" class="form-control form-control-sm" name="for_ans_sen">
                        </div>
                        <div class="form-check">
                            <label for="for_src"><strong>Source</strong></label>
                            <input id="f_src" type="checkbox" class="form-control form-control-sm" name="for_src">
                        </div>
                        <div class="form-check">
                            <label for="for_time"><strong>Time</strong></label>
                            <input id="f_time" type="checkbox" class="form-control form-control-sm" name="for_time">
                        </div>
                    </div>
                    <div class="modal-body">

                        <div class="d-flex">
                            <div id="sandbox-container" class="m-1">
                                <div class="input-group date">
                                    <input id="startDate" type="text" class="form-control" placeholder="start"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div>
                            </div>

                            <div id="sandbox-container" class="m-1">
                                <div class="input-group date">
                                    <input id="endDate" type="text" class="form-control" placeholder="end"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div>
                            </div>
                            <div>
                                <button id="filter" class="btn btn-lg btn-outline-dark ml-1 mt-2 p-1" type="button" onclick="getFilteredHistory()"><i class="fa fa-filter" aria-hidden="true">&nbsp</i>
                                </button>
                            </div>
                            <div>
                                <button id="allGuesses" class="btn btn-lg btn-outline-dark ml-1 mt-2 p-1" type="button" onclick="getRecentGuesses()"><i class="fa fa-info-circle" aria-hidden="true">&nbsp</i>
                                </button>
                            </div>
                        </div>

                        <div class="accordion" id="accordionExample">

                            <!--  recent data -->

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form>
            <!-- fateTextModel -->
            <input type="text" style="display:none" name="model_rtfp">
            <input type="text" style="display:none" name="model_rtfc">
            <input type="text" style="display:none" name="model_rtfn">
            <input type="text" style="display:none" name="model_rtfs">
            <input type="text" style="display:none" name="model_rtfw">
            <input type="text" style="display:none" name="model_guess">
            <input type="text" style="display:none" name="model_question">
            <input type="text" style="display:none" name="model_answer">
            <input type="text" style="display:none" name="model_step">
        </form>


        <div class="row" style="margin-bottom: 5px;">
            <!-- step 1 -->
            <div class="col-md-12 col-sm-12" id="step-1" style="display:none ;">
                <div class="card text-white bg-primary border-dark">
                    <div class="card-header">
                        <h5 class="card-title">Step -1 : Guess the blanked out word:</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text" id="random-sentence-view">With supporting text below as a natural lead-in to additional content.</p>

                    </div>
                    <div class="card-footer text-muted">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-warning btn-md" onclick="restart()">New</button>
                            </div>
                            <input type="text" class="form-control" id="guessInput" placeholder="" onkeypress="return enterEventHandler(event)">
                            <div class="input-group-append">
                                <button id="guessButton" class="btn btn-success" onclick="step2()" type="button">Guess</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 5px;">
            <!-- step 2 -->
            <div class="col-md-12 col-sm-12 mt-sm-12" id="step-2" style="display:none">
                <div class="card text-white bg-primary border-dark">
                    <div class="card-header">
                        <h5 class="card-title">Step -2 : Ask a question about the sentence, itself.</h5>
                    </div>
                    <div class="card-footer text-muted">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <a href="#" type="button" id="backButton2" class="btn btn-warning" onclick="backToPreviousStep()">Back</a>
                            </div>
                            <input type="text" class="form-control" id="questionInput" onkeypress="enterEventHandler(event)">
                            <div class="input-group-append">
                                <button id="askButton" class="btn btn-success" onclick="step3()" type="button">Ask</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- step 3 -->

        <div class="row" style="margin-bottom: 5px;">
            <div class="col-md-12 col-sm-12 mt-sm-12" id="step-3" style="display:none">
                <div class="card text-white bg-primary border-dark ">
                    <div class="card-header">
                        <h5 class="card-title">Step -3 : Answer your question</h5>
                    </div>
                    <div class="card-body ">
                        <div id="full-text-view" class="card-text col-md-auto">
                            <p>With supporting text below as a natural lead-in to additional content.</p>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <div style="margin-bottom: 10px;" class="row">
                            <div class="col-md-6">
                                <h6> Question: </h6>
                                <p class="card-text" id="question-view"> Your question goes here </p>
                            </div>
                            <div class="col-md-6">
                                <h6> Text: </h6>
                                <p class="card-text" id="text-name-view"> Text name goes here </p>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <a href="#" type="button" id="backButton3" class="btn  btn-md btn-warning" onclick="backToPreviousStep()">Back</a>
                            </div>
                            <input type="text" class=" form-control" onkeypress="enterEventHandler(event)" id="answerInput" placeholder="">
                            <div class="input-group-append">
                                <button id="answerButton" class="btn btn-success" onclick="finish()" type="button">Answer</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- step final -->
        <div class="row">
            <div class="col-md-12 col-sm-12 mt-sm-12" id="step-final" style="display:none">
                <div class="card text-white bg-primary border-dark">
                    <div class="card-header row">
                        <h5 class="col-md-8 card-title">Finished! Thank you for playing.</h5>
                        <a type="button" id="restartButton" style="float: right;" href="#" class="col-md-4 btn btn-success mb-2" onclick="restart()">Play again</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="endView"></div>


    </div>

    <script>
        //jquery
        // only holds the filtered data
        var temp_recent_games;

        $(document).ready(function() {
            init();

            $('#sandbox-container .input-group.date').datepicker({
                orientation: "bottom auto",
                autoclose: true,
                todayHighlight: true,
                format: "mm-dd-yyyy",
            });

            let check_array = [];

            $('input[type=checkbox]').click(function() {
                let ch_name = $(this).prop("name");
                let ch_status = $(this).prop("checked");

                if (ch_status == true) {
                    check_array.push(ch_name)
                } else {
                    check_array.splice(check_array.indexOf(ch_name), 1)
                }
                printFormatedData(check_array);
            });

        });

        function printFormatedData(c_arr) {

            $('#accordionExample').empty();
            let res = JSON.parse(temp_recent_games);
            let i;

            if (res.length > 0) {
                for (i = 0; i < res.length; i++) {
                    $('#accordionExample').append(headerFromater(c_arr, res, i));
                }
            } else {
                $('#accordionExample').append("<p>No records found!</p>");
            }
        }

        function headerFromater(c_arr, res, i) {
            let data;
            let tempSen = res[i].guess_sen.replace(res[i].guess_ans, "________");
            data = `<div class="card">
                            <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse"
                            data-target="#collapse${i}" aria-expanded="true" aria-controls="collapse${i}">
                             <strong>` + getArrValue(c_arr, res, i, tempSen) + `  </strong>
                                 </button>
                            </h2>
                        </div>
                        <div id="collapse${i}" class="collapse" aria-labelledby="headingOne"
                        data-parent="#accordionExample">
                            <div class="card-body">
                                <div>
                                    <h6><u>Sentence </u></h6><p>${tempSen}</p>
                                    <h6><u>Guess Word:</u> <span><strong>${res[i].user_guess_wor}</strong></span></h6><br>
                                    <h6><u>Correct Word:</u> <span><strong>${res[i].guess_wor}</strong></span></h6><br>
                                </div>
                                <div>
                                    <h6><u>Question about the sentence</u></h6><p><strong>${res[i].question}</strong></p>
                                    <h6><u>Answer :</u> <span><strong>${res[i].answer}</strong></span></h6><br>
                                </div>
                                <div><h6><u>Text file :</u></h6><p><strong>${res[i].file_name}.txt</strong></p></div>
                                <div><a class="btn btn-sm btn-primary" id="${res[i].guess_id}" href = "#" onclick="viewHistoryGame(this)"> View more</a></div>
                        </div>
                        </div>
                    </div>`
            return data;
        }

        function getArrValue(arr, res, j, tempSen) {

            let temp = "";
            if (arr.length != 0) {
                for (let i in arr) {
                    if (arr[i] == "for_guess")
                        temp += res[j].guess_wor + " | "
                    if (arr[i] == "for_ans_word")
                        temp += res[j].user_guess_wor + " | "
                    if (arr[i] == "for_question")
                        temp += res[j].question + " | "
                    if (arr[i] == "for_ans_sen")
                        temp += res[j].answer + " | "
                    if (arr[i] == "for_src")
                        temp += res[j].file_name + ".txt" + " | "
                    if (arr[i] == "for_time")
                        temp += res[j].created_at + " | "
                }
            } else {
                temp += res[j].user_guess_wor + " | " + res[j].question
            }

            return temp;
        }

        function init() {
            generateFateTextModel();
        }

        /**
         * generate a random sentence from one of the text files in the data directory
         */
        function generateFateTextModel() {
            $.ajax({
                beforeSend: function() {
                    $("body").addClass("loading");
                },
                type: "POST",
                url: <?php echo '"' . TEXTLOADER_URL . '"' ?>,
                data: {
                    cmd: <?php echo CMD_A_RANDOM_SENTENCE ?>
                },
                success: function(data) {
                    //console.log(data);
                    var res = JSON.parse(data);
                    //set the model
                    $("input[name=model_rtfp]").val(res.rtfp);
                    $("input[name=model_rtfc]").val(res.rtfc);
                    $("input[name=model_rtfn]").val(res.rtfn);
                    $("input[name=model_rtfs]").val(res.rtfs);
                    $("input[name=model_rtfw]").val(res.rtfw);
                    $("input[name=model_guess]").val(res.guess);
                    $("input[name=model_question]").val(res.question);
                    $("input[name=model_answer]").val(res.answer);
                    $("input[name=model_step]").val(res.step);

                    //clear input fields
                    clearInputs();

                    //console.log(res.rtfc);
                    step1();


                },
                complete: function(data) {
                    // Hide image container
                    $("body").removeClass("loading");
                }

            });
        }

        /**
         * prepare for step 1
         */
        function step1() {
            //set the sentence view
            var step = $("input[name=model_step]").val();
            var sen = $("input[name=model_rtfs]").val();
            var word = $("input[name=model_rtfw]").val();
            sen = sen.replace(word, "__________");

            if (step == 1 & sen != "") {
                //remove breaklines <br> at the begining of the sentence while displaying
                var cleanSen = sen.replace(/^<br>/g, "").trim();
                while (cleanSen.startsWith('<br>')) {
                    cleanSen = cleanSen.replace(/^<br>/g, "");
                }
                $("#random-sentence-view").html(cleanSen);
                //show step-1 div
                $("#step-1").show("slow");
                $("input[name=model_step]").val(2);
                //auto focus guessinput
                $('#guessInput').focus();
            }
        }

        /**
         * step-2 , guessing word step handler
         * get user guess word, set to the model
         * view step 2 view
         */
        function step2() {
            var step = $("input[name=model_step]").val();
            if (step == 2) {
                //get guess word and validate it then set the model
                var guessWord = $("#guessInput").val();
                $("input[name=model_guess]").val(guessWord);

                //show step-2 div
                $("#step-2").show("slow");
                $("input[name=model_step]").val(3);
                //auto focus questioninput
                $("#questionInput").focus();

                $("#guessButton").addClass("disabled");
                $("#guessInput").prop("readonly", true);

                //insert correct word in step 1
                var sen = $("input[name=model_rtfs]").val();
                var word = $("input[name=model_rtfw]").val();
                var blankedWordSpan = "<span id='blankedWordSpan'>" + word + "</span>";
                sen = sen.replace(word, blankedWordSpan);
                $("#random-sentence-view").html(sen);
            }

        }

        /**
         * step-3 , asking question step handler
         * get user question, set to the model
         * view step 3 view
         */
        function step3() {

            var step = $("input[name=model_step]").val();

            if (step == 3) {
                //get user question and validate it then set the model
                var question = $("#questionInput").val();
                $("input[name=model_question]").val(question);

                //set the full text viewer
                var textContent = $("input[name=model_rtfc]").val();
                var sen = $("input[name=model_rtfs]").val();
                var word = $("input[name=model_rtfw]").val();

                var senWithSpan = "<span id='senSpan'>" + sen + "</span>";
                var wordWithSpan = "<span id='wordSpan'>" + word + "</span>";
                senWithSpan = senWithSpan.replace(word, wordWithSpan);

                textContent = textContent.replace(sen, senWithSpan);
                $("#full-text-view").html(textContent);

                //set the question view- and the text-name-view
                $("#question-view").text($("input[name=model_question]").val());
                $("#text-name-view").text($("input[name=model_rtfn]").val());

                //show step-3 div
                $("#step-3").show("slow");
                $("input[name=model_step]").val(0);
                //auto focus answerinput
                $("#answerInput").focus();
                //slide to step-3 div
                $('html,body').animate({
                    scrollTop: $("#step-3").offset().top
                }, 'slow');

                $("#askButton").addClass("disabled");
                $("#backButton2").addClass("disabled");
                $("#questionInput").prop("readonly", true);

                centerTargetSenInFullTextViewer();
            }

        }

        function finish(logGuess = true) {
            var step = $("input[name=model_step]").val();
            if (step == 0) {
                //get user answer and validate it then set the model
                var answer = $("#answerInput").val();
                $("input[name=model_answer]").val(answer);


                //show step-final div
                $("#step-final").show("slow");
                $("input[name=model_step]").val(1);

                //auto focus restart button
                $("#restartButton").focus();
                //slide to final div
                $('html,body').animate({
                    scrollTop: $("#step-final").offset().top
                }, 'slow');


                $("#answerButton").addClass("disabled");
                $("#backButton3").addClass("disabled");
                $("#answerInput").prop("readonly", true);
                if (logGuess) {
                    addCurrentGuess();
                }

            }
        }

        function restart() {
            location.reload();
        }

        function centerTargetSenInFullTextViewer() {
            $('#full-text-view').animate({
                scrollTop: $("#senSpan").offset().top - $("#full-text-view").offset().top - 100
            }, 2000);
        }

        /**
         * clear guess/question/answer input fields
         */
        function clearInputs() {
            $('#guessInput').val('')
            $("#answerInput").val('');
            $('#questionInput').val('');
        }

        function enterEventHandler(event) {
            var KeyCode = event.KeyCode || event.which;
            if (KeyCode === 13) { //on enter key press
                goToNextStep(event);
            }
        }

        //handling backspace key and enter key -- back to previous step -- goto next step
        //
        $(document).keydown(function(event) {
            var KeyCode = event.KeyCode || event.which;
            if (KeyCode === 8 && event.target.id != 'guessInput' && event.target.id != 'questionInput' && event.target.id != 'answerInput') { //on backspace key press
                backToPreviousStep();
            } else if (KeyCode === 13) { //on enter key press
                goToNextStep(event);
            }

        });
        $(document).keypress(function(event) {
            var KeyCode = event.KeyCode || event.which;
            if (KeyCode === 8) { //on backspace key press
                backToPreviousStep();
            } else if (KeyCode === 13) { //on enter key press
                goToNextStep(event);
            }

        });

        function goToNextStep(event) {
            var step = $("input[name=model_step]").val();
            if (step == 2) {
                step2();
            } else if (step == 3) {
                step3();
            } else if (step == 0) {
                finish();
            } else if (step == 1) {
                restart();
            }
            event.preventDefault();
            return false;
        }

        function backToPreviousStep() {
            var step = $("input[name=model_step]").val();
            if (step == 3 || step == 2) {
                $("#step-2").hide("slow");
                $("#guessButton").removeClass("disabled");
                $("#guessInput").prop("readonly", false);
                $("#guessInput").val("");
                $("input[name=model_step]").val(2);
            } else if (step == 0) {
                $("#step-3").hide("slow");
                $("#askButton").removeClass("disabled");;
                $("#questionInput").prop("readonly", false);
                $("#questionInput").val("");
                $("#backButton2").removeClass("disabled");
                $("input[name=model_step]").val(3);
            }
        }

        function getRecentGuesses(s_date = null, e_date = null) {
            $.ajax({
                type: "POST",
                url: <?php echo '"' . TEXTLOADER_URL . '"' ?>,
                data: {
                    cmd: <?php echo CMD_GET_RECENT_HISTORY ?>,
                    s_date: <?php echo 's_date' ?>,
                    e_date: <?php echo 'e_date' ?>
                },
                success: function(data) {

                    $('#accordionExample').empty();
                    temp_recent_games = data;
                    var res = JSON.parse(data);
                    var i;

                    if (res.length > 0) {
                        for (i = 0; i < res.length; i++) {

                            var tempSen = res[i].guess_sen.replace(res[i].guess_ans, "________");

                            $('#accordionExample').append(
                                `<div class="card">
                            <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse"
                            data-target="#collapse${i}" aria-expanded="true" aria-controls="collapse${i}">
                             <strong> ${res[i].user_guess_wor} | ${res[i].question}</strong>
                                 </button>
                            </h2>
                        </div>
                        <div id="collapse${i}" class="collapse" aria-labelledby="headingOne"
                        data-parent="#accordionExample">
                            <div class="card-body">
                                <div>
                                    <h6><u>Sentence </u></h6><p>${tempSen}</p>
                                    <h6><u>Guess Word:</u> <span><strong>${res[i].user_guess_wor}</strong></span></h6><br>
                                    <h6><u>Correct Word:</u> <span><strong>${res[i].guess_wor}</strong></span></h6><br>
                                </div>
                                <div>
                                    <h6><u>Question about the sentence</u></h6><p><strong>${res[i].question}</strong></p>
                                    <h6><u>Answer :</u> <span><strong>${res[i].answer}</strong></span></h6><br>
                                </div>
                                <div><h6><u>Text file :</u></h6><p><strong>${res[i].file_name}.txt</strong></p></div>
                                <div><a class="btn btn-sm btn-primary" id="${res[i].guess_id}" href = "#" onclick="viewHistoryGame(this)"> View more</a></div>
                        </div>
                        </div>
                    </div>`);
                        }
                    } else {
                        $('#accordionExample').append("<p>No records found!</p>");
                    }

                }
            });
        }

        function addCurrentGuess() {
            $.ajax({
                type: "POST",
                url: <?php echo '"' . TEXTLOADER_URL . '"' ?>,
                data: {
                    cmd: <?php echo CMD_ADD_CURRENT_GUESS ?>,
                    guess_sen: $("input[name=model_rtfs]").val(),
                    user_guess_wor: $("input[name=model_guess]").val(),
                    guess_wor: $("input[name=model_rtfw]").val(),
                    question: $("input[name=model_question]").val(),
                    answer: $("input[name=model_answer]").val(),
                    content: $("input[name=model_rtfc]").val(),
                    fileName: $("input[name=model_rtfn]").val()
                },
                success: function(data) {
                    //console.log(data);
                    //var res = JSON.parse(data);
                    //console.log(data);
                }
            });
        }

        //recreat a give history game
        function viewHistoryGame(obj) {

            var guessId = $(obj).attr('id');
            //console.log("Viewing " + guessId);
            //close the modal
            $('#exampleModal').modal('hide');
            //get the fateTextModel
            $.ajax({
                type: "POST",
                url: <?php echo '"' . TEXTLOADER_URL . '"' ?>,
                data: {
                    cmd: <?php echo CMD_GET_GUESS ?>,
                    guess_id: guessId
                },
                success: function(data) {
                    //console.log(data);
                    var res = JSON.parse(data);
                    var guessFateTextModel = res[0];
                    //console.log(JSON.stringify(guessFateTextModel));

                    //set the model
                    $("input[name=model_rtfc]").val(guessFateTextModel.content);
                    $("input[name=model_rtfn]").val(guessFateTextModel.file_name);
                    $("input[name=model_rtfs]").val(guessFateTextModel.guess_sen);
                    $("input[name=model_rtfw]").val(guessFateTextModel.guess_wor);
                    $("input[name=model_guess]").val(guessFateTextModel.user_guess_wor);
                    $("input[name=model_question]").val(guessFateTextModel.question);
                    $("input[name=model_answer]").val(guessFateTextModel.answer);
                    $("input[name=model_step]").val(1);

                    //clear input fields
                    clearInputs();
                    step1();
                    //set the guessInput
                    $("#guessInput").val(guessFateTextModel.user_guess_wor);
                    step2();
                    //set the user question
                    $("#questionInput").val(guessFateTextModel.question);
                    step3();
                    //set the user answer
                    $("#answerInput").val(guessFateTextModel.answer);
                    finish(false); //false is passed cuz there is no need to log again

                }
            });

        }

        function getFilteredHistory() {
            if ($("#startDate").val() != "" && $("#endDate").val() != "") {
                var d_format = "YYYY-MM-DD";
                var s_date = moment(new Date($("#startDate").val())).format(d_format);
                var e_date = moment(new Date($("#endDate").val())).format(d_format);
                getRecentGuesses(s_date, e_date);
            } else {
                alert("Please select the range of the date");
            }
        }
    </script>

</body>

</html>
<?php
define('CMD_CLEAR_ALL', 1);
define('CMD_LOAD_ALL', 2);
define('CMD_GET_ALL_TEXT_FILES', 3);
define('CMD_A_RANDOM_SENTENCE', 4);
define('TEXTLOADER_URL', "http://www.questiontask.com/scripts/textloader.php");
//define('TEXTLOADER_URL', "http://localhost:8081/fatetext/scripts/textloader.php");
//Fate text model

?>

<html>

<head>
    <title>Guess A Word</title>

    <!-- Latest compiled and minified CSS -->
    <!-- Bootstrap v4.3.1 CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/textloader.css">



    <!-- jQuery library -->
    <script type="text/javascript" src="js/jquery-3.6.0.min.js"> </script>


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
            margin-top: 30px;
        }

        #full-text-view {
            padding: 10px;
            white-space: pre-wrap;
            max-height: 300px;
            overflow-y: auto;
        }

        #full-text-view #senSpan {
            color: darkred;
            font-weight: bold;
        }

        #full-text-view #senSpan #wordSpan {
            text-decoration: underline;
        }
    </style>

</head>

<body>
    <div class="overlay"></div>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Guess A Word </a>
            </div>
        </div>
    </nav>

    <div class="container">


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


        <div class="row">
            <!-- step 1 -->
            <div class="col-md-6 col-sm-12" id="step-1" style="display:none">
                <div class="card ">
                    <div class="card-header">
                        <h5 class="card-title">Step -1 : Guess the blanked out word:</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text" id="random-sentence-view">With supporting text below as a natural lead-in to additional content.</p>

                    </div>
                    <div class="card-footer text-muted">
                        <form class="form-inline">
                            <div class="col-md-6 d-flex">
                                <div class="form-group mb-2">
                                    <input type="text" class="form-control" id="guessInput" placeholder="Guess a word">

                                    <button type="button" style="margin:0px 5px 0px 10px" id="guessButton" class="btn btn-primary btn-sm" onclick="step2()">Guess</button>
                                    <button type="button" class="btn btn-warning btn-sm" onclick="restart()">New</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- step 2 -->
            <div class="col-md-6 col-sm-12 mt-sm-3" id="step-2" style="display:none">
                <div class="card ">
                    <div class="card-header">
                        <h5 class="card-title">Step -2 : Ask a question about the sentence, itself.</h5>
                    </div>
                    <div class="card-body">
                        <form class="form-inline">
                            <div class="col-md-12 d-flex">
                                <div class="form-group mb-2">
                                    <input type="text" class="form-control" id="questionInput" placeholder="Ask a question">
                                </div>
                                <div class="col-md-6">
                                    <a href="#step-3" type="button" id="askButton" class="btn btn-primary mb-2 btn-sm" onclick="step3()"> Ask</a>
                                    <a href="#" type="button" id="backButton2" class="btn btn-primary mb-2 btn-sm btn-warning" onclick="backToPreviousQuestion()">Back</a>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
        <br />

        <!-- step 3 -->
        <div class="row" id="step-3" style="display:none">
            <div class="card ">
                <div class="card-header">
                    <h5 class="card-title">Step -3 : Answer your question</h5>
                </div>
                <div class="card-body">
                    <div id="full-text-view" class="card-text col-md-auto">
                        <p>With supporting text below as a natural lead-in to additional content.</p>
                    </div>
                </div>
                <div class="card-footer text-muted">
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
                    <div class="form row">
                        <input type="text" class=" col-md-9 form-control" id="answerInput" placeholder="Answer">
                        <div class="col-md-3">
                            <a href="#step-final" type="button" id="answerButton" class="btn btn-primary mb-2 btn-sm" onclick="finish()">Answer</a>
                            <a href="#" type="button" id="backButton3" class="btn btn-primary mb-2 btn-sm btn-warning" onclick="backToPreviousQuestion()">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br />
        <!-- step final -->
        <div class="row" id="step-final" style="display:none">
            <div class="card ">
                <div class="card-header">
                    <h5 class="card-title">Finished! Thank you for playing.</h5>
                </div>
                <div class="card-body">
                    <h6> To play again press the restart button. </h6>
                    <a type="button" href="#" class="btn btn-primary mb-2" onclick="restart()">Restart</a>
                </div>
            </div>
        </div>


    </div>

    <script>
        //jquery

        $(document).ready(function() {
            init();
        });


        function backToPreviousQuestion() {
            var step = $("input[name=model_step]").val();
            if (step == 3 || step == 2) {
                $("#step-2").css('display', 'none');
                $("#guessButton").removeClass("disabled");
                $("#guessInput").prop("readonly", false);
                $("input[name=model_step]").val(2);
            } else if (step == 0) {
                $("#step-3").css('display', 'none');
                $("#askButton").removeClass("disabled");;
                $("#questionInput").prop("readonly", false);
                $("#backButton2").removeClass("disabled");
                $("input[name=model_step]").val(3);
            }
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
                $("#random-sentence-view").html(sen);
                //show step-1 div
                $("#step-1").css('display', 'block');
                $("input[name=model_step]").val(2);
            }
        }

        /**
         * step-2 , guessing word step handler
         * get user guess word, set to the model
         * view step 2 view
         */
        function step2() {
            if ($('#guessInput').val() != '') {
                var step = $("input[name=model_step]").val();
                if (step == 2) {
                    //get guess word and validate it then set the model
                    var guessWord = $("#guessInput").val();
                    $("input[name=model_guess]").val(guessWord);

                    //show step-2 div
                    $("#step-2").css('display', 'block');
                    $("input[name=model_step]").val(3);
                    $("#guessButton").addClass("disabled");
                    $("#guessInput").prop("readonly", true);
                }
            } else {
                alert("Pleas provide your guess word");
            }
        }

        /**
         * step-3 , asking question step handler
         * get user question, set to the model
         * view step 3 view
         */
        function step3() {
            if ($('#questionInput').val() != '') {

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
                    $("#step-3").css('display', 'block');
                    $("input[name=model_step]").val(0);
                    $("#askButton").addClass("disabled");
                    $("#backButton2").addClass("disabled");
                    $("#questionInput").prop("readonly", true);

                    centerTargetSenInFullTextViewer();
                }
            } else {
                alert("Please write your question");
            }
        }

        function finish() {

            if ($("#answerInput").val() != '') {
                var step = $("input[name=model_step]").val();
                if (step == 0) {
                    //get user answer and validate it then set the model
                    var answer = $("#answerInput").val();
                    $("input[name=model_answer]").val(answer);


                    //show step-final div
                    $("#step-final").css('display', 'block');
                    $("input[name=model_step]").val(0);
                    $("#answerButton").addClass("disabled");
                    $("#backButton3").addClass("disabled");
                    $("#answerInput").prop("readonly", true);

                }
            } else {
                alert("Write your answer!");
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
    </script>

</body>

</html>
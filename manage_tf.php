<?php
define('CMD_CLEAR_ALL', 1);
define('CMD_LOAD_ALL', 2);
define ('CMD_GET_ALL_TEXT_FILES', 3);
//define ('TEXTLOADER_URL', "http://localhost:8081/fatetext/scripts/textloader.php");
define ('TEXTLOADER_URL', "https://www.questiontask.com/scripts/textloader.php");
?>

<html>
<head>
    <title>Text File Loader</title>

    <!-- Latest compiled and minified CSS -->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/textloader.css">



    <!-- jQuery library -->
    <script type="text/javascript" src="js/jquery-3.6.0.min.js"> </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


    <link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"
          rel = "stylesheet">
    <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

    <style>
        .overlay{
            display: none;
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 999;
            background: rgba(255,255,255,0.8) url("loader.gif") center no-repeat;
        }
        /* Turn off scrollbar when body element has the loading class */
        body.loading{
            overflow: hidden;
        }
        /* Make spinner image visible when body element has the loading class */
        body.loading .overlay{
            display: block;
        }

        .ui-widget-header {
            background: #cedc98;
            border: 1px solid #DDDDDD;
            color: darkred;
            font-weight: bold;
        }
    </style>

</head>
<body>
<div class="overlay"></div>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Dashboard -- Text File loader</a>
        </div>
        <ul class="nav navbar-nav">
            <li id="homeNavButt" onclick="homeNavButHandler(this)"><a  href="#">Home</a></li>
            <li class="active" id="showTextFilesNavButt" onclick="showTextFilesHandler(this)" ><a  href="#">Show Text Files</a></li>
            <li id="clearTextFilesNavButt" onclick="clearTextFilesHandler(this)"  ><a  href="#">Clear All</a></li>
            <li id="loadAllTextFilesNavButt" onclick="loadAllTextFilesHandler(this)" ><a  href="#">Load All</a></li>
        </ul>
    </div>
</nav>

<div class="container">

    <!-- list text files loaded -->
    <div id="listOfBooks">
        <h2> List of text files</h2>
        <table id="listOfBooksTable" class="table listOfBooksTable">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Type</th>
                <th scope="col">Loaded</th>
            </tr>
            </thead>
                <tbody >

                </tbody>
        </table>
    </div>

    <!-- load text files-->
    <div id="loadTextFiles" class="card text-center">
        <div class="card-header">
            <h2> Loading text files</h2>
        </div>
        <div class="card-body">
            <div id = "progressbar-1"></div>
        </div>
        <div class="card-footer text-muted">
            <div id="loadingProgress" >

            </div>
        </div>
    </div>



</div>

<script>
    //jquery


    $(document).ready(function () {
        $("#listOfBooks").show();
        $("#loadTextFiles").hide();

        $('#homeNavButt').click();
        //setTimeout(function(){ $('#homeNavButt').click()}, 100);

        // Add remove loading class on body element based on Ajax request status
        $(document).on({
            ajaxStart: function(){
                $("body").addClass("loading");
            },
            ajaxStop: function(){
                $("body").removeClass("loading");
            }
        });
    });

    function showTextFilesHandler(obj) {
        //remove previous active element
        $(obj).parent().find("li.active").removeClass("active");
        $(obj).addClass("active");
        $("#listOfBooks").show();
        $("#loadTextFiles").hide();

        //get the textfiles in the db
        $.ajax({
            type: "POST",
            url: <?php echo '"'. TEXTLOADER_URL .'"'?>,
            data: { cmd: <?php echo CMD_GET_ALL_TEXT_FILES?> },
            success: function (data)  {
                var listOfBooks = JSON.parse(data);
                var listOfBooksView = "";
                listOfBooks.forEach( function(book, index) {
                    var loadStsusIcon = book['isLoaded'] == true ? "fa-check" : "fa-times";

                    var  book = '<tr>' +
                        '<th scope ="row">' + (index+1) + '</th>' +
                        '<td>' +  book['titlestr'] + '</td>' +
                        '<td>' +  book['type'] + '</td>' +
                        '<td>' + '<span><i class="fa ' + loadStsusIcon +'"' + 'aria-hidden="' + 'true"></i></span></td>' +
                        '</tr>';
                    listOfBooksView += book;
                });
                $("#listOfBooksTable tbody").html(listOfBooksView);

            }

        });
    }

    function clearTextFilesHandler(obj){

        //confirm
        var confirm = window.confirm("Are you sure you want to clear all loaded text files?");
        console.log("confirm="+confirm);
        if(confirm){
            $.ajax({
                type: "POST",
                url: <?php echo '"'. TEXTLOADER_URL .'"'?>,
                data: { cmd: <?php echo CMD_CLEAR_ALL?> },
                success: function (data)  {
                    var res = JSON.parse(data);
                    if(res.status){
                        alert(res.message);
                        $('#homeNavButt').click();
                    }else{
                        alert(res.message);
                        $('#homeNavButt').click();
                    }
                }

            });
        }else{

        }

    }
    function loadAllTextFilesHandler(obj,firstTime=true){
        if(firstTime){
            //remove previous active element
            $(obj).parent().find("li.active").removeClass("active");
            $(obj).addClass("active");

            $("#listOfBooks").hide();
            $("#loadTextFiles").show();

            $( "#progressbar-1" ).progressbar({
                value: 0,
                max : 100
            });

        }
        $.ajax({
            type: "POST",
            url: <?php echo '"'. TEXTLOADER_URL .'"'?>,
            data: { cmd: <?php echo CMD_LOAD_ALL?> },
            success: function (data)  {
                var res = JSON.parse(data);
                if(res.status){
                    var totalTextFiles = res.allTextFiles;
                    var unloadedTextFiles =res.unloadedTextFiles;
                    var loadedTextFilesPerc = (totalTextFiles - unloadedTextFiles)/totalTextFiles * 100;
                    console.log("perc ="+loadedTextFilesPerc);
                      if(res.unloadedTextFiles > 0){
                          $( "#progressbar-1" ).progressbar( "value", loadedTextFilesPerc );
                          loadAllTextFilesHandler(obj,false);
                      }else{
                          $( "#progressbar-1" ).progressbar( "value", loadedTextFilesPerc );
                          alert("Completed!");
                      }
                }
                $("#loadingProgress").text(res.message);
            }

        });

    }
    function homeNavButHandler(obj){
        //remove previous active element
        $(obj).parent().find("li.active").removeClass("active");
        $(obj).addClass("active");

        showTextFilesHandler(obj);
    }
</script>

</body>
</html>



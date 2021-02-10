<link href="../web/css/bootstrap.min.css" rel="stylesheet">
    <link href="../web/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../web/css/animate.css" rel="stylesheet">
    <link href="../web/css/plugins/codemirror/codemirror.css" rel="stylesheet">
    <link href="../web/css/plugins/codemirror/ambiance.css" rel="stylesheet">
    <link href="../web/css/style.css" rel="stylesheet">
<body>
<textarea style="width:100%; height:90%; border:none"  id="code2">
    	<?php print file_get_contents($_REQUEST['pagename']);?>
    </textarea>
    <!-- Mainly scripts -->
    <script src="../web/js/jquery-2.1.1.js"></script>
    <script src="../web/js/bootstrap.min.js"></script>
    <script src="../web/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../web/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- CodeMirror -->
    <script src="../web/js/plugins/codemirror/codemirror.js"></script>
    <script src="../web/js/plugins/codemirror/mode/javascript/javascript.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../web/js/inspinia.js"></script>
    <script src="../web/js/plugins/pace/pace.min.js"></script>


    <script>
         $(document).ready(function(){


             var editor_two = CodeMirror.fromTextArea(document.getElementById("code2"), {
                 lineNumbers: true,
                 matchBrackets: true,
                 styleActiveLine: true
             });

        });
    </script>
    </body>
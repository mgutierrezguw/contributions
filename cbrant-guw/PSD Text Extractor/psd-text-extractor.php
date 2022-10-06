<?php

    if (isset($_POST['file'])) {
        $file = fopen($_POST['file'], 'r');
        $eofFlag = "</x:xmpmeta>";
        $textOpenFlag = "<photoshop:LayerText>";
        $textCloseFlag = "</photoshop:LayerText>";
        $thisLine = "";
        $textInFile = "<h1>Text In " . $_POST['file'] . "</h1><br />";
        $textArray = [];
        $inTextBlock = false;

        if ($file) {
            while(!str_contains($thisLine, $eofFlag)) {
                $thisLine = fgets($file);
                if ($thisLine == false) {
                    $thisLine = $eofFlag;
                } else {
                    if ($inTextBlock) {
                        array_push($textArray, str_replace($textCloseFlag, "", $thisLine));
                        if (str_contains($thisLine, $textCloseFlag)) {
                            array_push($textArray, "<br /><br />");
                            $inTextBlock = false;
                        }
                    } else if (str_contains($thisLine, $textOpenFlag)) {
                        $inTextBlock = true;
                        array_push($textArray, str_replace($textOpenFlag, "", str_replace($textCloseFlag, "", $thisLine)));
                        if (str_contains($thisLine, $textCloseFlag)) {
                            array_push($textArray, "<br /><br />");
                            $inTextBlock = false;
                        }
                    }
                }
            }
        }

        for ($i=count($textArray)-1; $i > -1; $i--) {
            $textInFile .= $textArray[$i];
        }

        fclose($file);

        echo $textInFile;
        exit;
    }

?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title>PSD Text Extractor</title>
        <meta name="description" content="Extracts text assets from a PSD / PSB to ease manual copy work">
        <meta name="author" content="Cody Brant (GetUWired)">

        <style>
            /* BOILERPLATE STUFF */

            * { 
                box-sizing: border-box;
            }

            html, body {
                min-height: 100%;
                font-size: calc(12px + (18 - 12) * (100vw - 800px) / (1600 - 800));
            }

            body {
                display: grid;
                width: 100vw;
                height: 100vh;
                font-size: 1rem;
                font-family: Verdana, sans-serif;
                line-height: 1.5rem;
                text-rendering: optimizeLegibility;
                grid-template-rows: 0.5fr 0.5fr auto 1fr; grid-template-columns: 21.875fr 56.25fr 21.875fr;
            }

            body, ul, ol, dl {
                margin: 0;
            }

            article, aside, audio, footer, header, nav, section, video {
                display: block; 
            }
                
            p { 
                -ms-word-break: break-all;
                word-break: break-all;
                word-break: break-word;
                -moz-hyphens: auto;
                -webkit-hyphens: auto;
                -ms-hyphens: auto;
                hyphens: auto;
            } 

            textarea { 
                display: block; 
                resize: none;
                width: 100%;
                height: 100%;
                border-radius: 3px;
                border: 0px;
                padding: 0.3rem;
                overflow: auto;
                font-size: 1rem;
            }
            
            table { 
                border-collapse: collapse; 
            }

            td {
                padding: .5rem;
            }
                
            img { 
                border: none;
                max-width: 100%;
            }
                
            input[type="submit"]::-moz-focus-inner, input[type="button"]::-moz-focus-inner {
                border : 0px;
            }
                
            input[type="search"] { 
                -webkit-appearance: textfield;
            } 

            input[type="submit"] { 
                -webkit-appearance: none;
            }
                
            input:required:after {
                color: #f00;
                content: " *";
            }

            input[type="email"]:invalid { 
                background: #f00;
            }

            input[type="submit"] {
                width: auto;
            }

            input {
                display: block; 
                width: auto;
                border-radius: 3px;
                border: 0px;
                padding: 0.3rem;
                font-size: 1rem;
                margin: 0.3rem;
            }

            /* BUTTONS */
            .button {
                display: block;
                margin: 1.2rem;
                margin-left: 0.3rem;
                margin-right: 0.3rem;
                color: white;
                background-image: radial-gradient(#494949, #3d3d3d);
                border: none;
                border-radius: 0.5rem;
                padding: 0.6rem;
                text-align: center;
                font-weight: 600;
                font-weight: 1rem;
                width: auto;
            }

            .button:hover {
                cursor: pointer;
                background-image: radial-gradient(#5c5c5c, #474747);
            }

            .green.button {
                background-image: radial-gradient(#1ccc12, #14b30b);
            }

            .green.button:hover {
                background-image: radial-gradient(#28c41c, #23a51a);
            }

            form {
                display: table;
                width: auto;
                margin-left: auto;
                margin-right: auto;
                background-color: gainsboro;
                border-radius: 8px;
                box-shadow: 0px 0px 0.5rem lightgray;
                padding: 2rem;
                overflow: auto;
            }
                
            .right.aligned { 
                float: right;
                margin-left: 2rem;
                clear: right;
            }

            .left.aligned { 
                float: left;
                margin-right: 2rem;
                clear: left;
            }

            .centered {
                margin-left: auto;
                margin-right: auto;
                text-align: center;
            }

            /* TEXT COLORS */
            .white {
                color: white;
            }

            .black {
                color: black;
            }

            .gray {
                color: gray;
            }

            .capitalized {
                text-transform: uppercase;
            }

            h1 {
                font-size: 3rem;
                line-height: 4.25rem;
                font-weight: 600;
            }

            h2 {
                font-size: 2rem;
                line-height: 3rem;
                font-weight: 600;
            }

            h3 {
                font-size: 1.7rem;
                line-height: 2.9rem;
                font-weight: 600;
            }

            h4 {
                font-size: 1.4rem;
                line-height: 2rem;
                font-weight: 600;
            }

            h5 {
                font-size: 1.25rem;
                line-height: 1.8rem;
                font-weight: 600;
            }

            h6 {
                font-size: 1.1rem;
                line-height: 1.65rem;
                font-weight: 600;
            }

            label {
                font-size: 0.8rem;
                font-weight: 600;
            }

            .inline-block {
                display: inline-block;
            }

            .block {
                display: block;
            }

            .inline {
                display: inline;
            }

            .css.grid, .grid.container {
                display: grid;
                row-gap: 1rem;
                column-gap: 1rem;
            }



            /* MEDIA QUERIES FOR SMALL RESOLUTIONS (PHONES) */

            @media screen and (max-width: 800px) {
                html {
                    font-size: 12px;
                }
            }



            /* MEDIA QUERY FOR LARGE RESOLUTONS (TV) */

            @media screen and (min-width: 1600px) {
                html {
                    font-size: 18px;
                }
            }



            /* MAIN */

            #top-page-margin, #header, #footer, #navbar {
                grid-column: 1 / span 3;
            }

            #top-page-margin {
                grid-row: 1 / span 2;
            }

            #header {
                grid-row: 1;
            }

            #navbar {
                grid-row: 2;
            }

            #left-page-margin, #left-side-bar {
                grid-row: 3;
                grid-column: 1;
            }

            #right-page-margin, #right-side-bar {
                grid-row: 3;
                grid-column: 3;
            }

            #right-side-bar {
                margin-left: auto;
            }

            #bottom-page-margin, #footer {
                grid-row: 4;
                grid-column: 1 / span 3;
            }

            #page-content {
                width: 100%;
                min-width: 275px;
                max-width: 1200px;
                height: 100%;
                min-height: calc(400px - 17.5rem);
                padding: 1rem;
                margin-left: auto;
                margin-right: auto;
            }
        </style>
    </head>
    <body>
        <div id="top-page-margin" class="empty grid cell"></div>

        <div id="left-page-margin" class="empty grid cell"></div>
        <div id="page-content" class="centered content grid cell">
            <h4>Select a PSD to Extract Text From</h4>
            <form action="psd-text-extractor.php" method="POST" autocomplete="false">
                <label class="left aligned block" for="file">PSD Files:</label><br />
                <select name="file" id="file">
                <?php

                    $psds = array_filter(scandir("."), function($file) { return str_contains($file, ".psd") || str_contains($file, ".psb"); });

                    foreach ($psds as $psd) {
                        echo "<option value='" . $psd . "'>" . str_replace(".psd", "", str_replace(".psb", "", $psd)) . "</option>";
                    }

                ?>
                </select>
                <br /><br />
                <div style="width: 100%;">
                    <input class="right aligned green button" type="submit" value="Extract Text" />
                </div>
            </form>
        </div>
        <div id="right-page-margin" class="empty grid cell"></div>

        <div id="bottom-page-margin" class="empty grid cell"></div>

        <!-- IE HTML5SHIV STUFF -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/platform/1.3.5/platform.min.js"></script>
        <script id="html5shiv_include" src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script id="respondmin_include" src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <script id="ie_workaround_logic">
            if (platform.name !== "IE") {
                document.getElementById("html5shiv_include").remove();
                document.getElementById("respondmin_include").remove();
            }
            document.getElementById("ie_workaround_logic").remove();
        </script>
        
        <!-- OTHER JS INCLUDES -->
        <script src="js/main.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    </body>
</html>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Maze Generator </title>
        <?php
            include 'inc/functions.php';
        ?>
        
        <link href="css/styles.css" rel ="stylesheet" type="text/css"/>
        
    </head>
    <body>
        <header>
            <h1>Maze Generator</h1>
        </header>
        
        <div id="main">
            <div>
                Generates mazes via some algorithm I came up with and ported to PHP. <br/>
                Guarantees solvability, and that every tile can be visited. <br/>
                <a href="files/MazeC.txt">C code</a><br/>
                <a href="files/MazeAuto.exe">Windows Executable (Automatic iteration)</a><br/>
                <a href="files/MazeManual.exe">Windows Executable (Manual iteration)</a><br/>
                <a href="files/MazePHP.txt">PHP code</a><br/>
                
            </div>
            <div id="inputs">
                <form id = "controls" method="post" action="index.php">
                    <label>Height:</label>
                    <input type="number" alt = "height" name="height" min="5" max="100" step="1"><br/>
                    <label>Width:</label> 
                    <input type="number" name="width" min="5" max="100" step="1">
                    <br/><input type="submit" value="Generate">
                </form>
            </div>
                <?php
                    $width= $_POST["width"];
                    $height= $_POST["height"];
                    if ($width < 5){
                        $width = 5;
                    }
                    if ($height < 5){
                        $height = 5;
                    }
                    GenerateMaze($width, $height)
                ?>
        </div>
    </body>
            
</html>

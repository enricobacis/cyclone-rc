<!DOCTYPE html>
<html>
  
<?php

// Setting variables
$extension = end(explode(".", $_FILES["file"]["name"]));
$filename = $_FILES["file"]["name"];
$file = "/tmp/" . $filename;
$out = $file . ".out";

?>

  <head>
    
    <title><?php echo $filename; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    
    <!-- JQuery -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js" type="text/javascript"></script>

    <!-- div expander -->
    <script src="js/div.expander.js" type="text/javascript"></script>
    
  </head>
  
  <body>

<?php

// Check for file size and extension
if (($_FILES["file"]["size"] < 100 * 1024) && $extension == "cyc")
{
  // Check for errors in file
  if ($_FILES["file"]["error"] > 0)
  {
    echo "Error: " . $_FILES["file"]["error"] . "<br>";
  }
  else
  {
    // Save file to /tmp
    move_uploaded_file($_FILES["file"]["tmp_name"], $file);

    // Compile time
    echo "<br><h2>Compile output</h2>";

    ob_start();
    system("rm -rf " . $out);
    system("cyclone -o " . $out . " " . $file . " 2>&1");
    $compile_out = ob_get_clean();
    echo "<pre>" . htmlspecialchars($compile_out, ENT_QUOTES);

    // Check if compilation was OK
    if (file_exists($out))
    {
      // Execute time
      echo "OK</pre>";
      echo "<h2>Execute output</h2>";
      ob_start();
      system("chmod +x " . $out);
      system($out . " 2>&1");
      $execute_out = ob_get_clean();
      echo "<pre>" . htmlspecialchars($execute_out, ENT_QUOTES);
    }

    echo "</pre>";
    echo "<br><p>Press F5 to recompile</p>";

    // Show file info
    echo "<br><br>";
    echo "<h2 id=\"expanderHead\" style=\"cursor:pointer;\">";
    echo "File details (click to <span id=\"expanderSign\">show</span>)</h2>";

    echo "<div id=\"expanderContent\" style=\"display:none; overflow:hidden;\" >";
    echo "Name: " . $_FILES["file"]["name"] . "<br>";
    echo "Type: " . $_FILES["file"]["type"] . "<br>";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";

    $text = file_get_contents($file);
    echo "<h4>Source file </h4>";
    echo "<pre><code>" . htmlspecialchars($text, ENT_QUOTES) . "</code></pre>";

    echo "</div>";
  }
}
else
{
  echo "<p>ERROR: File must be smaller than 100 kB and have extension .cyc</p>";
}
?>

<div id="footer">
  <p>created by
    <a href="mailto:enrico.bacis@gmail.com?Subject=Cyclone%20Remote%20Compiler">
      enrico bacis (2013 
      <a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/deed.en_US">Creative Commons</a>)
    </a>
  </p>
</div>

</body>
</html>
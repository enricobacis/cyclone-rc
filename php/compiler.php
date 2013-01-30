<?php

require 'exectimeout.php';

// Setting variables
$extension = end(explode(".", $_FILES["file"]["name"]));
$filename = $_FILES["file"]["name"];
$file = "/tmp/" . $filename;
$out = $file . ".out";

// Check if file selected
if (empty($_FILES["file"]["name"])) {
  echo "<p class=\"error\">No file selected</p>";
}

// Check for file extension
elseif ($extension !== "cyc") {
  echo "<p class=\"error\">File must have extension <i>.cyc</i></p>";
}

// Check for file size
elseif ($_FILES["file"]["size"] >= 200 * 1024) {
  echo "<p class=\"error\">File must be smaller than 200 kB</p>";
}

// Check for errors in file
elseif ($_FILES["file"]["error"] > 0) {
  echo "<p class=\"error\">Error: " . $_FILES["file"]["error"] . "</p>";
}

// all ok
else
{
  // Save file to /tmp
  move_uploaded_file($_FILES["file"]["tmp_name"], $file);

  // Compile time
  echo "<br><h2>Compile output</h2>";

  //ob_start();
  system("rm -rf " . $out);
  $compile_out = ExecWaitTimeout("cyclone -o " . $out . " " . $file . " 2>&1");
  //ob_get_clean();
  echo "<pre class=\"console\">" . htmlspecialchars($compile_out, ENT_QUOTES);

  // Check if compilation was OK
  if (file_exists($out))
  {
    // Execute time
    echo "OK</pre>";
    echo "<h2>Execute output</h2>";
    //ob_start();
    system("chmod +x " . $out);
    $execute_out = ExecWaitTimeout($out . " 2>&1");
    //ob_get_clean();
    echo "<pre class=\"console\">" . htmlspecialchars($execute_out, ENT_QUOTES);
  }

  echo "</pre>";

  // Show file info
  echo "<br><h3 id=\"expanderHead\" style=\"cursor:pointer;\">";
  echo "File details (click to <span id=\"expanderSign\">show</span>)</h3>";

  echo "<div id=\"expanderContent\" style=\"display:none; overflow:hidden;\" >";
  echo "<p class=\"file_details\">";
  echo "Name: " . $_FILES["file"]["name"] . "<br>";
  echo "Type: " . $_FILES["file"]["type"] . "<br>";
  echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
  echo "</p>";

  // show source
  $text = file_get_contents($file);
  echo "<pre class=\"prettyprint linenums\"><code class=\"language-cs\">";
  echo HTMLSPECIALCHARS($text);
  echo "</code></pre>";

  echo "</div>";
}
?>

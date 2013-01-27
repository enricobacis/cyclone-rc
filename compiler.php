<html>
  <head>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>

<?php

// Setting variables
$extension = end(explode(".", $_FILES["file"]["name"]));
$file = "/tmp/" . $_FILES["file"]["name"];
$out = $file . ".out";

// Check for file size and extension
if (($_FILES["file"]["size"] < 100000) && $extension == "cyc")
{
  // Check for errors in file
  if ($_FILES["file"]["error"] > 0)
  {
    echo "Error: " . $_FILES["file"]["error"] . "<br>";
  }
  else
  {
    // Show file info
    echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    echo "Type: " . $_FILES["file"]["type"] . "<br>";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";

    // Save file to /tmp
    move_uploaded_file($_FILES["file"]["tmp_name"], $file);

    // Compile time
    echo "<br><h1>Compiling</h1><pre>";
    system("rm -rf " . $out);
    system("cyclone -o " . $out . " " . $file . " 2>&1");

    // Check if compilation was OK
    if (file_exists($out))
    {
      // Execute time
      echo "OK</pre>";
      echo "<h1>Executing</h1><pre>";
      system("chmod +x " . $out);
      system($out . " 2>&1");
    }

    echo "</pre>";

    echo "<br><br><br><h2>source</h2><pre><code>";
    system("cat " . $file);
    echo "</code></pre>";
  }
}
else
{
  echo "File must be .cyc";
}
?>

</body>

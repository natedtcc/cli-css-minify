#!/usr/bin/php
<?php # minify.php - Nate Nasteff 2020

// A simple CSS minifier to be run from the command line.

// If no args are received from CL or help is requested..

if ($argc != 2 || in_array($argv[1], array('--help', '-help', '-h', '-?'))) { 
  
?>

CSS command-line minifier.
Takes an argument of .css file and outputs 
the minified CSS into a .min.css file, retaining 
the original name.

    Usage:
    minify <foo.css>

<?php    
} 

else {
  // Define needles for stripping newlines and spaces

  $needles = ["\n", " "];

  // Define regex patterns to strip comments

  $regex = array(
    "`^([\t\s]+)`ism"=>'',
    "`^\/\*(.+?)\*\/`ism"=>"",
    "`([\n\A;]+)\/\*(.+?)\*\/`ism"=>"$1",
    "`([\n\A;\s]+)//(.+?)[\n\r]`ism"=>"$1\n",
    "`(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+`ism"=>"\n"
    );

  // Check that the file name is valid, and assign contents to css_str

  if (file_exists($argv[1])){

    $css_str = file_get_contents($argv[1]);

    // Remove comments, strip whitespaces / newlines

    $css_str = preg_replace(array_keys($regex), $regex, $css_str);
    $css_str = str_replace($needles, "", $css_str);

    // Strip trailing semicolons at the end of css definitions

    $css_str = str_replace(";}", "}", $css_str);

    // Update filename

    $minified_filename = str_replace(".css", ".min.css", $argv[1]);

    // Attempt to save the new minified CSS
    try {
      file_put_contents($minified_filename, $css_str);
      } 
    
    catch (Exception $e) {
      echo $e->getMessage();
      }

    echo "Minified CSS file written to " . $minified_filename ."\n";
}

// Make sure file is actually a valid CSS file..

else if (!strpos($argv[1], '.css')) {
  echo "Not a valid CSS file!";
}

// If no file is found / incorrect filename..

else {
  echo "File not found or incorrect filename!";
}

}
?>

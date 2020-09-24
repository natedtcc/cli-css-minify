#!/usr/bin/php
<?php # minify.php - CSS minifier for CLI - Nate Nasteff 2020

// If no args are received from CLI or help is requested, print instructions..

if ($argc == 1 || in_array($argv[1], array('--help', '-help', '-h', '-?'))) { 
  
?>

CSS command-line minifier.
Takes an argument of .css file and outputs 
the minified CSS into a .min.css file, retaining 
the original name.

    Usage:
    minify <foo.css>

<?php    
} 

// Make sure no second argument gets passed at CLI
// TODO: Allow multiple css files at once

elseif ($argc > 2) { 
?>

Invalid second argument.

    Usage:
    minify <foo.css>

<?php
}

else {

  // Define regex patterns to strip comments

  $regex = [
    "`^([\t\s]+)`ism"=>'',
    "`^\/\*(.+?)\*\/`ism"=>"",
    "`([\n\A;]+)\/\*(.+?)\*\/`ism"=>"",
    "`([\n\A;\s]+)//(.+?)[\n\r]`ism"=>"",
    "`(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+`ism"=>"",
    "/\s+/"=>'',
    "/;}/"=>'}'
  ];

  // Check that the file name is valid
  // TODO: Add logic for multiple files

  if (file_exists($argv[1])){

    // Define anonymous func to return a minified string

    $minify = function() use (&$argv, &$regex){ 
        $css_str = file_get_contents($argv[1]);
        return preg_replace(array_keys($regex), $regex, $css_str);
    };

    // Update filename

    $minified_filename = str_replace(".css", ".min.css", $argv[1]);

    // Attempt to save the new minified CSS
    try {
      file_put_contents($minified_filename, $minify());
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
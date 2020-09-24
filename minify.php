#!/usr/bin/php
<?php # minify.php - CSS minifier for CLI - Nate Nasteff 2020

/* Define regex patterns to strip whitespaces, newlines,
** comments and certain semicolons from a string of CSS.
*/ 
      
$regex = [
    "`^([\t\s]+)`ism"=>'',
    "`^\/\*(.+?)\*\/`ism"=>"",
    "`([\n\A;]+)\/\*(.+?)\*\/`ism"=>"",
    "`([\n\A;\s]+)//(.+?)[\n\r]`ism"=>"",
    "`(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+`ism"=>"",
    "/\s+/"=>'',
    "/;}/"=>'}'
];

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

    /* Define func to return a minified CSS string.
    ** Takes an argument of str (unmodified .css string).
    */

    function minify($css_str, $regex){
      return preg_replace(array_keys($regex), $regex, $css_str);
    }
  
    /* Define func to validate the
    ** file. If it exists, and is a valid .css
    ** file, return a minified .css file extension
    ** ie test.css => test.min.css
    */

    $validate = function() use ($argv){
      $x = !file_exists($argv[1]) 
        ? null
        : (!strpos($argv[1], '.css') 
          ? null 
          : str_replace(".css", ".min.css", $argv[1]));
      return $x;
    };

    /* Define anonymous function to write the new .min.css file
    ** to disk. Checks that $midified_filename has been validated,
    ** then passes the filename as the first arg for file_put_contents.
    ** The second argument calls minify(), which returns a modified CSS
    ** string. Returns a string to be printed to the user to
    ** notify them of success or failure
    */

    $write_out = function($minified_filename) use($argv, $regex) {
      if (isset($minified_filename)){
        try {
          file_put_contents(
            $minified_filename, minify(
              file_get_contents($argv[1]), $regex));
          }
        catch (Exception $e) {
          return "I/O Error! Check your file permissions." . PHP_EOL;
          }
        return "Minified CSS file written to " . $minified_filename . PHP_EOL;
      }
      else return "Minify error - Not a valid CSS file / invalid filename!" . PHP_EOL;
    };

    /* Call write_out, with $validate() func as arg, returning
    ** a string to be printed to the user for confirmation.
    */

    print($write_out($validate()));
}

?>

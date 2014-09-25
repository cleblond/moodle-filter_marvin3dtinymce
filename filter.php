<?php 
////////////////////////////////////////////////////////////////////////
//  EasyOChem marvin3dtinymce filter for use with eomarvin2d TinyMCE plugin
// 
//  This filter will replaces <mar3d></mar3d>
// with the Javascript needed to display the molecular structure inline
// with MarvinView 
//  To activate this filter, go to admin and enable 'marvin3dtinymce' 
// which will also display the button in TinyMCE editor.
//
//
//  Filter written by Carl LeBlond
//
////////////////////////////////////////////////////////////////////////

class filter_marvin3dtinymce extends moodle_text_filter {

function filter($text, array $options = array()){
    global $CFG, $tinymce3d_applet_has_been_initialised;
    
  
$callbackfunction='';


if(preg_match_all("'\[.*?{.*?\[(.*?)\]}\]|<mar3d>(.*?)</mar3d>'si", $text, $matches)){

$count = 0;

$callbackfunction = '

global $count;
$a=$count++;
if($a=="")$a=0;





$mrvfile=$matches[0];
//echo "<br>a,mrv= $a <br> $mrvfile";
$mrvfile=str_replace( "[{[", "", $mrvfile);
if(preg_match_all( "/\[(.*?){(.*?)\[/", $mrvfile, $dimensions))
{
//var_dump($dimensions);

//echo "<br>".$dimensions[0];
//echo "<br>".
$width=$dimensions[1][0];
$heigth=$dimensions[2][0];
}
else{
$width=300;
$heigth=300;
}

$mrvfile=preg_replace( "/\[(.*?){(.*?)\[/", "", $mrvfile);



$mrvfile=str_replace( "]}]", "", $mrvfile);


$mrvfile=str_replace( "<mar3d>", "", $mrvfile);
$mrvfile=str_replace( "</mar3d>", "", $mrvfile);
$mrvfile=str_replace( "&lt;", "<", $mrvfile);
$mrvfile=str_replace( "&gt;", ">", $mrvfile);
$mrvfile=addslashes($mrvfile);


$replace="

<script type=\"text/javascript\">


mview_name = \"MSketch$a\";


mview_begin(\"http://'.$_SERVER['HTTP_HOST'].'/marvin\", $width, $heigth); //arguments: codebase, width, height
mview_param(\"tabScale\", \"50\");
mview_param(\"mol\", \"$mrvfile\");
mview_param(\"implicitH\", \"all\");
mview_param(\"importConv\", \"H+\");
//mview_param(\"selectable\", \"false\");
mview_param(\"animate\", \"all\");
mview_param(\"navmode\", \"rot3d\");
mview_param(\"rendering\", \"ballstick\");
mview_param(\"animFPS\", \"20\");

mview_end();
</script>
";

return $replace;




';



}

$search = "'\[.*?{.*?\[(.*?)\]}\]|<mar3d>(.*?)</mar3d>'si";


$newtext = preg_replace_callback($search, create_function('$matches', $callbackfunction), $text);

 
if(($newtext != $text) && !isset($tinymce3d_applet_has_been_initialised)){
      $$tinymce3d_applet_has_been_initialised = true;
           
             
$newtext = '
<script LANGUAGE="JavaScript1.1" SRC="http://'.$_SERVER['HTTP_HOST'].'/marvin/marvin.js"></script>
'.$newtext;



  } 
return $newtext;
}
}
?>

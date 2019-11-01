<?php
// CONFIG 
$smileStringStart =':'; // prefix for smileys by default is :
$smileStringEnd   =':'; // suffix for smileys by default is :

?><!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <title>smileys.local</title>

    <style type="text/css">
        body {
            margin:0 auto;             
            font-size: 13px; 
			font-family: courier new;
            background-color: #dadada;
        }

        .box {
            width: 100%;
            float:left;
            padding: 0.5em;
            margin: 0;
        }

        .white {
            background-color: #fff;
            color:black;
            padding:7px;
        }

        .black {
            background-color: #000;
            color:white;
            padding:7px;
        }

        .smiley {
            vertical-align: middle;
            max-height: 48px;                /* important for svg smileys */
            max-width: 128px;                /* important for svg smileys */
            margin: 3px;
        }
        .comments {
            color: #dadada;
        }

        .repeated {
            color: red;
            font-weight: bold;
        }        
        .text2replace {
            font-size: 19px!important;             
        }
        .smileypath {
            color: grey;
        }
    </style>

</head>
<body>

<?php
// GLOBALS
$smileys=array();
$smileyTexts=array();

// SECURITY
if (isset($_GET["search"])) {
    $_GET["search"]=strip_tags($_GET["search"]);
    $_GET["search"]=htmlspecialchars($_GET["search"]);
}
?>

# Custom Smileys<br>
# Images are seen relatively from the smiley directory lib/images/smileys</br>
# TEXT_TO_REPLACE         FILENAME_OF_IMAGE<br>
#<br>

<form action="/index.php" method="get" style="float:right">
  <input type="text" name="search" value="" placeholder=""><br>
  <input type="submit" value="search smileys" style="float:right">
</form>

<?php
// RECURSIVE SMILEYS LIST (*.gif)
$path = realpath('.');
$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST); // con directorios
foreach($objects as $name => $object){
    if ( ($object->getFilename()!=".") && ($object->getFilename()!="..")   && ($object->getFilename()!="folder.jpg") && (validExtension($object->getPathname())) ) 
    {
        if (isset($_GET["search"]))
        {
            if ($_GET["search"]=="")
            {
                $_GET["search"]=".";
            }

            // filter by search string
            if (substr_count(substr($object->getPathname(),strlen(__DIR__)+1),$_GET["search"]))
            {
                echo smiley($object);
            }
 
        }else{
            echo smiley($object);
        }
    }
}

function smiley($object){
    $colsize1=45;
    $colsize2=57;

    // filename to TEXT_TO_REPLACE
    $file=$object->getFilename();
    $text2replace=$GLOBALS["smileStringStart"].strtoupper(substr($file,0,-4)).$GLOBALS["smileStringEnd"];

    // smiley img
    $path=substr($object->getPathname(),strlen(__DIR__)+1);
    $path_img=str_replace('\\','/',$path);
    $pathclean="local/".$path_img;

    // colums
    if ($colsize1>strlen($file)){
        $columns1=str_repeat("&nbsp;",$colsize1-strlen($file));
    }else{
        $columns1=str_repeat("&nbsp;",$colsize1);
    }
    if ($colsize2>strlen($pathclean)){
        $columns2=str_repeat("&nbsp;",$colsize2-strlen($pathclean));
    }else{
        $columns2=str_repeat("&nbsp;",$colsize2);
    }

    // repeated TEXT_TO_REPLACE?
    if (in_array($text2replace,$GLOBALS["smileyTexts"]))
    {
        $repeated_css="repeated";
        $repeated_msg=" # REPEATED";
    }else{
        $repeated_css="norepeared";
        $repeated_msg="";
        $GLOBALS["smileyTexts"][]=$text2replace;
    }
 
    $out="<div>";
    $out.='<img src="'.$path_img.'" class="smiley white">';
    $out.='<img src="'.$path_img.'" class="smiley black">';    
    $out.='<span class="text2replace '.$repeated_css.'"><b>'.$text2replace.'</b></span>'."\r\n";
    $out.=$columns1."\r\n";
    $out.='<span class="smileypath">'.$pathclean."</span>\r\n";
    $out.=$columns2."\r\n";
    $out.='<span class=" '.$repeated_css.'">'.$repeated_msg.'</span>'."\r\n";
    $out.='</div>';
    $out.="\r\n\r\n";
    
    return $out;
} 

function validExtension($filename){
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
    
    $validExtensions=array("gif","png","jpg","svg");
    if (in_array(strtolower($ext), $validExtensions))
    {
        return true;
    } else{
        return false;
    }
        
}

?>
</body>
</html>
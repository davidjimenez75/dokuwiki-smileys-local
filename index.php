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
            <?php 
            if (isset($_GET["mode"]))
            {
                if ($_GET["mode"]==6) {
                    echo 'background-color: #fff;';
                }elseif ($_GET["mode"]==7) {
                    echo 'background-color: #000;';        
                }else{
                    echo 'background-color: #dadada;';
                }
            }else{
                echo 'background-color: #dadada;';
            }
            ?>
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
if (!isset($_GET["mode"]) || (!is_numeric($_GET["mode"]))) {
    $_GET["mode"]=1;
}

if ($_GET["mode"]==1){
    echo "
# Custom Smileys<br>
# Images are seen relatively from the smiley directory lib/images/smileys</br>
# TEXT_TO_REPLACE         FILENAME_OF_IMAGE<br>
#<br>";
}


?>


<form action="/index.php" method="get" style="float:right;clear:both;">
<input type="text" name="search" value="" placeholder=""  style="float:right;clear:both;">
  <select name="mode" style="float:right;clear:both;">
  <option value="1"<?php if ($_GET["mode"]==1) echo "selected"; ?> >1. smileys.local.conf</option>
  <option value="2"<?php if ($_GET["mode"]==2) echo "selected"; ?> >2. dokuwiki</option>
  <option value="3"<?php if ($_GET["mode"]==3) echo "selected"; ?> >3. dokuwiki (with path)</option>
  <option value="4"<?php if ($_GET["mode"]==4) echo "selected"; ?> >4. smileys</option>
  <option value="5"<?php if ($_GET["mode"]==5) echo "selected"; ?> >5. smileys (replacetext)</option>
  <option value="6"<?php if ($_GET["mode"]==6) echo "selected"; ?> >6. smileys (WHITE)</option>
  <option value="7"<?php if ($_GET["mode"]==7) echo "selected"; ?> >7. smileys (BLACK)</option>  
</select>
<br>
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
 
// MODE 1 - smileys.local.conf
if ($_GET["mode"]==1){
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
}
elseif ($_GET["mode"]==2){
    // MODE 2 - DOKUWIKI (NO SMILEY PATH)
    $out="<div>";
    $out.='<img src="'.$path_img.'" class="smiley white">';
    $out.='<img src="'.$path_img.'" class="smiley black">';    
    $out.='<span class="text2replace '.$repeated_css.'"><b>'.$text2replace.'</b></span>'."\r\n";
    $out.='<span class="text2replace '.$repeated_css.'">&lt;nowiki&gt;'.$text2replace.'&lt;/nowiki&gt;</span>'."\r\n";
    $out.='<span class=" '.$repeated_css.'">'.$repeated_msg.'</span>'."\r\n";
    $out.='</div><br>';
    $out.="\r\n\r\n";
}elseif ($_GET["mode"]==3){
    // MODE 3 - DOKUWIKI (WITH SMILEY PATH)
    $out="<div>";
    $out.='<img src="'.$path_img.'" class="smiley white">';
    $out.='<img src="'.$path_img.'" class="smiley black">';    
    $out.='<span class="text2replace '.$repeated_css.'"><b>'.$text2replace.'</b></span>'."\r\n";
    $out.='<span class="text2replace '.$repeated_css.'">&lt;nowiki&gt;'.$text2replace.'&lt;/nowiki&gt;</span>'."\r\n";
    $out.='<span class="smileypath">&lt;sub&gt;'.$pathclean."&lt;/sub&gt;</span>\r\n";
    $out.='<span class=" '.$repeated_css.'">'.$repeated_msg.'</span>'."\r\n";
    $out.='</div><br>';
    $out.="\r\n\r\n";
}elseif ($_GET["mode"]==4){
    // MODE 4 - JUST SMILEYS
    $out="";
    $out.='<img src="'.$path_img.'" class="smiley white" title="'.$text2replace.'">';
    $out.='<img src="'.$path_img.'" class="smiley black" title="'.$text2replace.'">';
    $out.='<span class=" '.$repeated_css.'">'.$repeated_msg.'</span>'."\r\n";
    $out.="\r\n\r\n";
}elseif ($_GET["mode"]==5){
    // MODE 5 - JUST SMILEYS WITH TEXT
    $out="";
    $out.='<img src="'.$path_img.'" class="smiley white">';
    $out.='<img src="'.$path_img.'" class="smiley black">';    
    $out.='<span class="text2replace '.$repeated_css.'"><b>'.$text2replace.'</b></span>'."\r\n";
    $out.="\r\n\r\n";
}elseif ($_GET["mode"]==6){
    // MODE 6 - JUST SMILEYS (WHITE))
    $out='<img src="'.$path_img.'" class="white" title="'.$text2replace.'">';
}elseif ($_GET["mode"]==7){
    // MODE 7 - JUST SMILEYS (BLACK)
    $out='<img src="'.$path_img.'" class="black" title="'.$text2replace.'">';

}




else{
    $out="";
}

    // FINAL RETURN
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
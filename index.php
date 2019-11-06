<?php
/**
 * dokuwiki-smileys-local
 * 
 * @rev. 191106
 * 
 */


// CONFIG 
$smileStringStart =':'; // prefix for smileys by default is :
$smileStringEnd   =':'; // suffix for smileys by default is :

?><!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <title>smileys.local</title>

    <style type="text/css">
        body {
            margin: 0 auto;
            font-size: 13px; 
			font-family: courier new;
            <?php 
            if (isset($_GET["mode"]))
            {
                if ($_GET["mode"]==0) {
                    echo 'background-color: #fff;';
                }elseif ($_GET["mode"]==1) {
                    echo 'background-color: #fff;';        
                }elseif ($_GET["mode"]==6) {
                    echo 'background-color: #fff;';        
                }elseif ($_GET["mode"]==7) {
                    echo 'background-color: #000;';        
                }else{
                    echo 'background-color: #dadada;';
                }
            }else{
                echo 'background-color: #fff;';
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
        .smiley-homepage img{
            margin: 3px;                    /* separation on smileys on homepage */
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
        a {
            color:blue;
            text-decoration: none;
        }
        a:hover {
            color:red;
        }
        .folder {
            font-size: 1.6em;
            font-weight: bold;
            padding:5px;
            border: 1px solid #efefef;
            background-color: #f3f6f9;
            text-align: center;

        }
        .conf {
            size: 11px;
        }
        .title{
            font-size: 2em;
            font-weight: bold;
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
}else{
    $_GET["search"]="";
}
if (!isset($_GET["mode"]) || (!is_numeric($_GET["mode"]))) {
    $_GET["mode"]=0; // by default show smileys folder
}

?>
<form action="index.php" method="get" style="float:left;border:1px solid white; background-color:white">
<input type="text" name="search" value="<?php echo $_GET["search"];?>" placeholder="" style="float:left;" size="45">

<select name="mode" style="float:left;">
  <option value="0"<?php if ($_GET["mode"]==0) echo "selected"; ?> >0. smileys folders</option>
  <option value="1"<?php if ($_GET["mode"]==1) echo "selected"; ?> >1. smileys.local.conf</option>
  <option value="2"<?php if ($_GET["mode"]==2) echo "selected"; ?> >2. dokuwiki</option>
  <option value="3"<?php if ($_GET["mode"]==3) echo "selected"; ?> >3. dokuwiki (with path)</option>
  <option value="4"<?php if ($_GET["mode"]==4) echo "selected"; ?> >4. smileys</option>
  <option value="5"<?php if ($_GET["mode"]==5) echo "selected"; ?> >5. smileys (replacetext)</option>
  <option value="6"<?php if ($_GET["mode"]==6) echo "selected"; ?> >6. smileys (white bg)</option>
  <option value="7"<?php if ($_GET["mode"]==7) echo "selected"; ?> >7. smileys (black bg)</option>  
</select>

<input type="submit" value="search smileys" style="float:left">
</form>

<span class="title"><br><br><a href="./index.php"># Custom Smileys </a></span>
<br>
<?php

// header for copy and paste the smileys.local.conf
if ($_GET["mode"]==1){
    echo "
# Images are seen relatively from the smiley directory lib/images/smileys</br>
# TEXT_TO_REPLACE         FILENAME_OF_IMAGE<br>
# ";
}


if ($_GET["mode"]==0) {
    echo listFolders();
}else{
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
}

// FUNCTIONS --------------------------------

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
    if ($repeated_css=="repeated")
    {
        $out.=$columns2."\r\n";
        $out.='<span class=" '.$repeated_css.'">'.$repeated_msg.'</span>'."\r\n";
    }
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

}else{
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

function listFolders() {
        // RECURSIVE SMILEYS LIST (*.gif)
        $path = realpath('.');
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST); // con directorios
        foreach($objects as $name => $object){
            if ( ($object->getFilename()!=".") && ($object->getFilename()!="..")  && (is_dir($object->getPathname())) && (substr_count($object->getPathname(),".git")==0)   )
            {
                $dir=substr($object->getPathname(),strlen(__DIR__)+1);
                echo '<a href="index.php?search='.$dir.'&mode=1">';
                echo '<div class="folder">';
                //echo '<a href="index.php?search='.$dir.'&mode=7"> <small>(black bg)</small>';
                //echo '<a href="index.php?search='.$dir.'&mode=6"> <small>(white bg)</small>';
                echo $dir;
                echo '</a>';

                
                // SHOW folder.jpg
                if (file_exists(__DIR__.'/'.$dir.'/smileys.local.conf')){
                    echo '<br><a href="'.$dir.'/smileys.local.conf" target="_blank" class="conf"><small><small>Recommended smileys.local.conf </small></small></a>';
                }
                echo "</div>";
                
                // Realtime listing smileys on homepage
                if (is_dir(__DIR__.'/'.$dir.'/')){
                    echo '<a href="index.php?search='.$dir.'&mode=1" class="smiley-homepage">';
                    //echo '<br><img src="'.$dir.'/folder.jpg">';
                    listFolderSmileys($dir);
                    echo '</a>';                    
                }

                
            }
            
        }
}


function listFolderSmileys($dir) {
        echo "<br>";         
        // RECURSIVE SMILEYS LIST (*.gif)
        $path = realpath('./'.$dir);
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST); // con directorios
        foreach($objects as $name => $object){
            if ( ($object->getFilename()!=".") && ($object->getFilename()!="..")   && ($object->getFilename()!="folder.jpg") && (validExtension($object->getPathname())) ) 
            {
                echo '<img src="'.$dir."/".$object->getFilename().'" title="'.$object->getFilename().'">';
            }
        }
        echo "<br><br>";        
}



?>
</body>
</html>
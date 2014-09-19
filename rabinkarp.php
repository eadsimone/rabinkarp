<?php
/**
 * Created by JetBrains PhpStorm.
 * User: peche
 * Date: 7/31/13
 * Time: 3:51 PM
 * To change this template use File | Settings | File Templates.
 */

class StringMatching
{
    // property declaration
    public $dint = 256;
    public $_salida;
    public $_res=array();

    // method declaration
    public function RabinKarpSearch($pat, $txt, $q){

        global $dint;
        $d=$dint;

        $M = strlen($pat);
        $N = strlen($txt);
        //$i, $j;
        $p = 0;  // hash value for pattern
        $t = 0; // hash value for txt
        $h = 1;

        // The value of h would be "pow(d, M-1)%q"
        for ($i = 0; $i < $M-1; $i++)
            $h = ($h*$d)%$q;

        // Calculate the hash value of pattern and first window of text
        for ($i = 0; $i < $M; $i++)
        {
            $p = ($d*$p + $pat[$i])%$q;
            $t = ($d*$t + $txt[$i])%$q;
        }

// Slide the pattern over text one by one
        for ($i = 0; $i <= $N - $M; $i++)
        {

            // Check the hash values of current window of text and pattern
            // If the hash values match then only check for characters on by one
            if ( $p == $t )
            {
                /* Check for characters one by one */
                for ($j = 0; $j < $M; $j++)
                {
                    /*cout << "posible valor";//borre /n
                    cout << txt[i+j] << "\n";*/

                    if ($txt[$i+$j] != $pat[$j])
                        break;
                }
                if ($j == $M)  // if p == t and pat[0...M-1] = txt[i, i+1, ...i+M-1]
                {
                    //$this->_salida.= "Pattern found at index".$i."<br/>";
                    $this->_salida.= "Patrón encontrado en el índice ".$i."<br/>";
                    $this->_res[]=$i;
                }
            }

            // Calulate hash value for next window of text: Remove leading digit,
            // add trailing digit
            if ( $i < $N-$M )
            {
                $t = ($d*($t - $txt[$i]*$h) + $txt[$i+$M])%$q;

                // We might get negative value of t, converting it to positive
                if($t < 0)
                    $t = ($t + $q);
            }
        }


    }
}


//ejemplos:
//$txt = "abcabaabcabac";
//$pat = "abaa";

//$txt = "GEEKS FOR GEEEKE GEEKSKEEG";
//$pat = "GEEK";

//abcdddadddaabaababa
//aba

$patvacio=0;
$textovacio=0;

if(isset($_POST["texto"])){
    $txt=$_POST["texto"];
    if($txt!=''){
        $textovacio=1;

    }else{
        $sms->_salida="el texto esta vacio";
    }
}

if (isset($_POST["patron"])){
    $pat=$_POST["patron"];
    if($pat!=''){
        $patvacio=1;
    }else {
        $sms->_salida="el patron esta vacio";
    }
}

if($patvacio==1 && $textovacio==1){

    $q = 101;  // A prime number

    $sms = new StringMatching();
    $sms->RabinKarpSearch($pat, $txt, $q);


    $r=0;
    $abierto=0;
    $sal="";
    for($i=0;$i<strlen($txt);$i++){

        if( ($r< count($sms->_res)) && ($i == $sms->_res[$r]) ){

            if($abierto==1){
                $sal.="</font>";
                $abierto=0;
            }
            $sal.="<font style=\"background-color: yellow;\">";//."</font>";
            $abierto=1;
            $r++;
        }else{
            if( ($r>0) && ($sms->_res[$r-1]+strlen($pat))<= $i && ($abierto==1) ){
                $sal.="</font>";
                $abierto=0;
            }
        }
        $sal.=$txt[$i];
    }
    if($abierto==1){
        $sal.="</font>";
        $abierto=0;
    }

    $sms->_salida.=$sal;

}else{
    $sms->_salida="Por favor debe completar los campos para ver algun resultado";
}





?>

<!DOCTYPE HTML>
<html>
<head>
    <title>EAD Site</title>
    <link rel="shortcut icon" href="https://lh4.googleusercontent.com/-86NBDJQCd3s/AAAAAAAAAAI/AAAAAAAAAAA/R6msCUKcpcM/s96-c/photo.jpg" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,600,700" rel="stylesheet" />
    <script src="js/jquery.min.js"></script>
    <script src="js/config.js"></script>
    <script src="js/skel.min.js"></script>
    <noscript>
        <link rel="stylesheet" href="css/skel-noscript.css" />
        <link rel="stylesheet" href="css/style.css" />
        <link rel="stylesheet" href="css/style-desktop.css" />
    </noscript>
    <!--[if lte IE 9]><link rel="stylesheet" href="css/ie9.css" /><![endif]-->
    <!--[if lte IE 8]><script src="js/html5shiv.js"></script><link rel="stylesheet" href="css/ie8.css" /><![endif]-->
    <!--[if lte IE 7]><link rel="stylesheet" href="css/ie7.css" /><![endif]-->
</head>
<body>

<!-- Nav -->
<nav id="nav">
    <ul>
        <li><a href="#top">Algoritmo Karp-Rabin</a></li>
    </ul>
</nav>

<!-- Contact -->
<!--<div class="wrapper wrapper-style4">-->
<div >
    <article id="contact">
        <header>
            <h2>Ingrese texto y patron a buscar!</h2>
            <span></span>
        </header>
        <div>
            <div class="row">
                <div class="12u">
                    <form method="post" action="rabinkarp.php">
                        <div>
                            <div class="row half">
                                <div class="12u">
                                    <input type="text" name="patron" id="patron" placeholder="patron" />
                                </div>
                            </div>
                            <div class="row half">
                                <div class="12u">
                                    <textarea name="texto" id="texto" placeholder="texto"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="12u">
                                    <a href="#" class="button form-button-submit">Buscar</a>
                                    <a href="#" class="button button-alt form-button-reset">Borrar</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div
            <div class="row row-special">
                <div class="12u">
                    <h3>Solucion</h3>
                    <?php echo $sms->_salida;?>
                </div>
            </div>
        </div>
        <footer>
            <!--p id="copyright">
                &copy; 2012 Untitled | Images: <a href="http://fotogrph.com">fotogrph</a> | Design: <a href="http://html5up.net/">HTML5 UP</a>
						</p-->
        </footer>
    </article>
</div>


</body>
</html>

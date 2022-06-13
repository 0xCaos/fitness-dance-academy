<?php

require_once "../config.php";

require_once(SITE_ROOT . "/php/validSession.php");
require_once(SITE_ROOT . '/php/utilities.php');
require_once(SITE_ROOT . "/php/Models/Scheda.php");
require_once(SITE_ROOT . "/php/Models/Utente.php");


preventMaliciousCode($_GET);
if(isset($_GET['id']))
{
    $schedaId = $_GET['id'];
    $modelloUtente = new Utente;
    
    $esercizi = Scheda::getEserciziById($schedaId);

    $content = '<ol id="ex-container">';
    $i=0;
    foreach($esercizi as $es){
        $i++;
        $content .= 
            '<li class="ex">
                <img class="ex-img" src="/img/iconeEsercizi/'.$es['id'].'.png"/>
                <ul class="ex-descr">
                    <li class="ex-titolo">'.$i.". ".$es['nome'].'</li>
                    <li>Serie: '.$es['serie'].'</li>
                    <li>Ripetizioni: '.$es['ripetizioni'].'</li>
                    <li>Pausa: '.$es['riposo'].'s</li>
                </ul>
            </li>';
    }
    $content .= "</ol>";

    $htmlPage = file_get_contents("../html/areaprivata/visualizzaScheda.html");

    $footer = file_get_contents("../html/components/footer.html");

    $htmlPage = str_replace("<pageFooter/>", $footer, $htmlPage);
    $htmlPage = str_replace("<esercizi/>", $content, $htmlPage);

    $ruoloUtente = $modelloUtente->getRole($_SESSION['userId']);
    if ($ruoloUtente < 3) {
        $htmlPage = str_replace(
            "<a href='/areaprivata/prenotazione_corso.php' class='button button-transparent'>", 
            "<a href='/areaprivata/gestione_corso.php' class='button button-transparent'>", 
            $htmlPage
        );
        $htmlPage = str_replace(
            "<a href='/areaprivata/prenotazione_sessione.php' class='button button-transparent'>", 
            "<a href='/areaprivata/gestione_sessione.php' class='button button-transparent'>", 
            $htmlPage
        );
    }

    echo $htmlPage;
}

?>
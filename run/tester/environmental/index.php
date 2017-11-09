<!DOCTYPE html>
<html>
<head>
    <style>
        /* Center the loader */
        #loader {
            position: absolute;
            left: 50%;
            top: 50%;
            z-index: 1;
            width: 150px;
            height: 150px;
            margin: -75px 0 0 -75px;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Add animation to "page content" */
        .animate-bottom {
            position: relative;
            -webkit-animation-name: animatebottom;
            -webkit-animation-duration: 1s;
            animation-name: animatebottom;
            animation-duration: 1s
        }

        @-webkit-keyframes animatebottom {
            from { bottom:-100px; opacity:0 }
            to { bottom:0px; opacity:1 }
        }

        @keyframes animatebottom {
            from{ bottom:-100px; opacity:0 }
            to{ bottom:0; opacity:1 }
        }

        #myDiv {
            display: none;
            text-align: center;
        }
    </style>
</head>
<body style="margin:0;">

<h2>Environmentální testy</h2>
<h2>Návod</h2>
<ul>
    <li>Povinné
        <ol>
            <li>Nastavit prostředí [devel, master, local ...] a jejich url</li>
            <li>Nastavit podstránky: pole (umožňuje počet pokusů na podstránku) nebo sitemap.xml (nastavit počet opakování pro každou stránku stejně)</li>
        </ol>
    </li>

    <li>Libovolně
        <ol>
            <li>Nastavit kus kódu co lze ve stránce detekovat. Například [--MYCODE--]</li>
        </ol>
    </li>

    <li>Účel
        <ol>
            <li>Primárně: Jednoduché otestování načítání sitemap.xml nebo výčtu linků v různých prostředích.</li>
            <li>Sekundárně: Logování rychlostí jednotlivých stránek a vyhledávání regulárního výrazu v jejich obsahu.</li>
        </ol>
    </li>
</ul>

<div id="loader"></div>

<div style="display:none;" id="myDiv" class="animate-bottom">
    <?php

    /*
     * Testování okbase na masteru
     */

    require __DIR__ . '../../../../core/bootstrap.php';

    try {
        //require __DIR__ . '/tests/multitest.php';
        require __DIR__ . '/tests/okbase.php';
    } catch (Exception $e) {
        echo $e->getMessage(), "\n";
    }
    ?>
</div>

<script>
    document.getElementById("loader").style.display = "none";
    document.getElementById("myDiv").style.display = "block";
</script>

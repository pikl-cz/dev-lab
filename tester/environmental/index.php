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
<?php

/*
 * Testování okbase na masteru
 */

require __DIR__ . '../../../devLab/bootstrap.php';

try {
    //require __DIR__ . '/tests/multitest.php';
    require __DIR__ . '/tests/okbase.php';
} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}
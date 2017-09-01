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
</ul>

<?php

require __DIR__ . '/../devLab/bootstrap.php';

/*
 * TODO:
 * - link() - does page exists??
 * - save log to file - preparede fce saveLog()
 * - statistics
 *      - use graphics / via CSS3??
 * - find "holes" - sql injection ... etc.
 */

class visitor
{
    protected $url, $pages, $log, $logFile, $detect;

    protected $pageDetails = []; //headers and content


    public $stopwatch;

    /*
     * TODO:
     * dependency injection
     * @param \Assist\Stopwatch $stopwatch
     */
    function __construct()
    {
        $stopwatch = new \Assist\Stopwatch;
        $this->stopwatch = $stopwatch;
    }

    /*
     * @param string $filename http://
     */
    public function setFileToSaveLog($filename)
    {
        $this->logFile = $filename;
        return $this->logFile;
    }

    /*
     * @param string $environment LOCAL, DEVEL, MASTER, DEVEL1, ...
     * @param string $url http://
     */
    public function setUrl($environment, $url)
    {
        $this->url[$environment] = $url;
        return $this->url[$environment];
    }

    /*
     * @param array $pages List of pages to visit
     */
    public function setPages($pages)
    {
        if (!is_array($pages))
        {
            throw new Exception('Špatná vstupní data pro stránky.');
        }
        $this->pages = $pages;
        return $this->pages;
    }

    /*
      * @param string $subject User´s part of code to find in page
      */
    public function detect($subject)
    {
        if (!is_string($subject))
        {
            throw new Exception('Část kódu k detekci není ve formátu string.');
        }
        $this->detect = $subject;
        return $this->detect;
    }

    /*
     * Get sitemap and generate links to visit
     */
    public function setSitemap($file, $attempts = 1)
    {
        if (!file_exists($file))
        {
            throw new Exception($file . ' doesnt exist.');
        }
        $myXMLData = file_get_contents($file);
        $xml=simplexml_load_string($myXMLData) or die("Error: Cannot create object");
        $this->pages = [];
        foreach ($xml->url as $url)
        {
            $urlFromXmlToReplace = ''; // TODO: delete root of address via regular
            $this->pages[str_replace($urlFromXmlToReplace, "", $url->loc)] = $attempts;
        }
    }

    private function log($environment, $page, $time, $attempt, $pageData)
    {
        $this->log[$environment][$page][$attempt]['time'] = $time;
        $this->pageDetails[$environment][$page]['headers'] = $pageData['headers'];
        $this->pageDetails[$environment][$page]['content'] = $pageData['content'];

        if (isset($this->logFile))
        {
            $this->saveLog();
        }
    }

    private function saveLog()
    {
        return;
    }

    private function parseHeaders($headersFromCurl)
    {
        $data = explode("\n", $headersFromCurl);
        return $data;
    }

    private function link($page, $address)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $address);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data[$page] = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($data[$page], 0, $header_size);
        $headers = $this->parseHeaders($headers);
        $content = substr($data[$page], $header_size);
        curl_close($ch);
        return [
            'headers' => $headers,
            'content' => $content,
        ];
    }

    private function scan()
    {
        foreach ($this->url as $environment => $url)
        {
            unset($page);
            foreach ($this->pages as $page => $count)
            {
                $address = $url . $page;
                $address = str_replace('//', '/', $address);
                for ($attempt = 1; $attempt <= $count; $attempt++)
                {
                    unset($pageData);
                    $start = $this->stopwatch->getImprint();
                    $pageData = $this->link($page, $address);
                    $end = $this->stopwatch->getImprint();
                    $time = $this->stopwatch->getExecutionTime($start, $end);

                    $this->log($environment, $page, $time, $attempt, $pageData);
                }
            }
        }
    }

    private function getReadableLog()
    {
        $result = [];
        foreach ($this->url as $environment => $url)
        {
            foreach ($this->log[$environment] as $page => $attempts)
            {
                foreach ($attempts as $key => $info)
                {
                    $row = new \stdClass();
                    $row->attempts = $attempts;
                    $row->headers = $this->pageDetails[$environment][$page]['headers'];
                    $row->content = $this->pageDetails[$environment][$page]['content'];
                    $result[$environment][$page] = $row;
                }
            }
        }
        return $result;
    }

    private function getAverageTime($attempts)
    {
        $total = 0;
        foreach($attempts as $att)
        {
            $total += $att['time']->unformatted;
        }
        return $total / count($attempts);
    }

    private function show()
    {
        $result = $this->getReadableLog();

        echo '<hr>';
        echo '<table>';
        echo '
            <thead>
                <td>Prostředí</td>
                <td>Stránka</td>
                <td>Počet pokusů</td>
                <td>Průměrný čas</td>
                <td>Detail</td>
                <td>HTTP</td>
            </thead>';

        foreach ($result as $environment => $pages)
        {
            foreach ($pages as $slug => $info)
            {
                $averageTime = $this->stopwatch->format($this->getAverageTime($info->attempts));
                echo '<tr>';
                echo '
                    <td><a href="' . $this->url[$environment] . '" target="_blank">' . $environment . '</a></td>
                    <td><a href="' . $this->url[$environment] . $slug . '" target="_blank">' . $slug . '</a></td>
                    <td>' . count($info->attempts) . '</td>
                    <td>' . $averageTime->min . 'min ' . $averageTime->sec . 'sec ' . $averageTime->ms . 'ms </td>                    
                ';
                echo '<td>';
                foreach($info->attempts as $detail)
                {
                    echo $detail['time']->min . ' ' . $detail['time']->sec . ' ' . $detail['time']->ms . '<br>';
                }

                echo '</td>';
                echo '<td>' . $info->headers[0] . '</td>';
                echo '</tr>';
            }
        }

        echo '</table>';
    }

    /*
     * Run programme
     */
    public function run()
    {
        $this->scan();
        $this->show();
    }
}

/*
 * pages => amount of visit
 */
$pages = [
    '/produkt/ts-4000/' => 2,
    '/cs/tag/wamp/' => 3,
    '/cs/2017/03/' => 1,
    '/zitkova/rezervace-a-cenik' => 1
];

try {
    $attacker = new visitor();
    $attacker->setUrl('devel', 'http://webar.pikl.cz');
    $attacker->setUrl('devel2', 'http://www.hotelkopanice.cz');

//    $attacker->setUrl('master', 'http://gezedata.cz');
    //$attacker->setUrl('local', 'http://devlab.dev');
    $attacker->setPages($pages);
    $attacker->detect('[MYCODE]');
    //$attacker->setSitemap('mujblogsitemap.xml', 2);

    $attacker->run();

} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}

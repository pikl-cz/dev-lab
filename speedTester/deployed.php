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
    protected $url, $pages, $log, $logFile;

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

    private function log($page, $time, $attempt)
    {
        //All environments
        foreach ($this->url as $environment)
        {
            $this->log[$environment][$page][$attempt]['time'] = $time;
        }

        if (isset($this->logFile))
        {
            $this->saveLog();
        }
    }

    private function saveLog()
    {
        return void;
    }

    private function link($page, $address)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $address);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data[$page] = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    private function scan()
    {
        foreach ($this->url as $url)
        {
            foreach ($this->pages as $page => $count)
            {
                $address = $url . $page;
                $address = str_replace('//', '/', $address);
                for ($attempt = 1; $attempt <= $count; $attempt++)
                {
                    $start = $this->stopwatch->getImprint();
                    $this->link($page, $address);
                    $end = $this->stopwatch->getImprint();
                    $time = $this->stopwatch->getExecutionTime($start, $end);
                    $this->log($page, $time, $attempt);
                }
            }
        }
    }

    private function getReadableLog()
    {
        $result = [];
        foreach ($this->url as $environment => $url)
        {
            foreach ($this->log[$url] as $page => $attempts)
            {
                foreach ($attempts as $key => $info)
                {
                    $row = new \stdClass();
                    $row->attempts = $attempts;
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

    private function print()
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
        $this->print();
    }
}

/*
 * pages => amount of visit
 */
$pages = [
    '/cs/category/doctrine-2/' => 5,
    '/cs/tag/wamp/' => 1,
    '/cs/2017/03/' => 1,
    'dsada' => 1
];

try {
    $attacker = new visitor();
    $attacker->setUrl('devel', 'http://pikl.cz');
    //$attacker->setUrl('master', 'http://webar.pikl.cz');
    $attacker->setUrl('local', 'http://devlab.dev');
    $attacker->setPages($pages);
    //$attacker->setSitemap('mujblogsitemap.xml', 2);

    $attacker->run();

} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}

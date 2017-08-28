<?php

require __DIR__ . '/../devLab/bootstrap.php';

/*
 * TODO:
 * - link() - does page exists??
 * - statistics
 *      - average time of links
 *      - use graphics / via CSS3??
 * - for different environments
 * - find "holes" - sql injection ... etc.
 */

class visitor
{
    protected $url, $pages, $log;

    /*
     * @param string $url http://
     */
    public function setMasterUrl($url)
    {
        $this->url = $url;
        return $url;
    }

    /*
     * @param array $pages List of pages to visit
     */
    public function setPages($pages)
    {
        /*
        if (!is_string($page) && !is_integer($count))
        {
            throw new Exception('Špatná vstupní data.');
        }
        */
        $this->pages = $pages;
        return $pages;
    }

    private function log($page, $time)
    {
        $this->log[$page][] = $time;
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
        $stopwatch = new \Assist\Stopwatch();
        foreach ($this->pages as $page => $count)
        {
            $address = $this->url . $page;
            $address = str_replace('//', '/', $address);
            for ($i = 1; $i <= $count; $i++)
            {
                $start = $stopwatch->getImprint();
                $this->link($page, $address);
                $end = $stopwatch->getImprint();
                $time = $stopwatch->getExecutionTime($start, $end);
                $this->log($page, $time);
            }

        }
    }

    public function run()
    {
        $this->scan();
        echo '<h1>' . $this->url . '</h1>';
        echo '<table>';
        dump($this->log);
/*
        foreach ($this->log as $page => $time)
        {
            echo '<tr><td>' . $page . '</td><td>' . $time . '</td></tr>';
        }
*/
        echo '</table>';
    }
}

$pages = [
    '/cs/category/doctrine-2/' => 10,
    '/cs/tag/wamp/' => 3,
    '/cs/2017/03/' => 5,
    'dsada' => 2
];

try {
    $attacker = new visitor();
    $attacker->setMasterUrl('http://webar.pikl.cz/');
   // $attacker->setDeployUrl('http://webar.pikl.cz/');
    $attacker->setPages($pages);
    $attacker->run();
} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}

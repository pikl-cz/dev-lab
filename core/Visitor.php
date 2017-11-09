<?php

namespace Assist;

/*
 * TODO: HTTPS se nenacitaji hlavicky
 * TODO: save log to file - preparede fce saveLog()
 * TODO: statistics - use graphics / via CSS3??
 * TODO: find "holes" - sql injection ... etc.
 * TODO: user friendly - design
 */


class Visitor
{
    protected $url, $pages, $log, $logFile, $detect, $batch;

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
    public function find($subject)
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
        if(filter_var($file, FILTER_VALIDATE_URL))
        {
            if(file_get_contents($file) === false)
            {
                throw new Exception('sitemap.xml is not loaded from ' . $file);
            }
            $response_xml_data = file_get_contents($file);
            $myXMLData = $response_xml_data;
        } else {
            if (!file_exists($file))
            {
                throw new Exception($file . ' doesnt exist.');
            }
            $myXMLData = file_get_contents($file);
        }

        $xml=simplexml_load_string($myXMLData) or die("Error: Nelze použít sitemapu.");
        $this->pages = [];
        foreach ($xml->url as $url)
        {
            $this->pages[parse_url($url->loc)['path']] = $attempts;
        }
    }

    private function log($environment, $page, $time, $attempt, $pageData)
    {
        $this->log[$environment][$page][$attempt]['time'] = $time;
        $this->pageDetails[$environment][$page]['headers'] = $pageData['headers'];
        $this->pageDetails[$environment][$page]['content'] = $pageData['content'];

        if (isset($this->detect) && !empty($this->detect) && strpos($pageData['content'], $this->detect) !== false)
        {
            $this->pageDetails[$environment][$page]['detect'] = true;
        } else {
            $this->pageDetails[$environment][$page]['detect'] = false;
        }
    }

    /*
     * Save copy of results in .html
     */
    private function saveLog($result)
    {
        $pathDir = 'logs/' . $this->logFile . '/';
        if (!file_exists($pathDir)) {
            mkdir($pathDir, 0777, true);
        }

        if (isset($this->logFile))
        {
            $handle = fopen($pathDir . $this->logFile . '-' . date('d-m-Y-h-i') . '.htm','w+');
            fwrite($handle, $result);
            fclose($handle);
        }
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

    private function makeBatch()
    {
        $batch = [];
        foreach ($this->url as $environment => $url)
        {
            unset($page);
            foreach ($this->pages as $page => $count)
            {
                $address = $url . $page;
                $address = str_replace('//', '/', $address);
                for ($attempt = 1; $attempt <= $count; $attempt++)
                {
                    $batch[] = [
                        'environment' => $environment,
                        'url' => $url,
                        'page' => $page,
                        'attempt' => $attempt,
                    ];
                }
            }
        }
        $this->batch = $batch;
    }

    private function scan()
    {
        foreach ($this->batch as $batch) {
            unset($pageData);
            $start = $this->stopwatch->getImprint();
            $pageData = $this->link($batch['page'], $batch['url']);
            $end = $this->stopwatch->getImprint();
            $time = $this->stopwatch->getExecutionTime($start, $end);

            $this->log($batch['environment'], $batch['page'], $time, $batch['attempt'], $pageData);
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
                    $row->detect = $this->pageDetails[$environment][$page]['detect'];
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
        $content = '';
        $content .= '<hr>';
        $content .= '<table id="results">';
        $content .= '
            <thead>
                <td>Prostředí</td>
                <td>Stránka</td>
                <td>Počet pokusů</td>
                <td>Průměrný čas</td>
                <td>Detail</td>
                <td>HTTP</td>
                <td>Detekován: ' . $this->detect . '</td>
            </thead>';

        foreach ($result as $environment => $pages)
        {
            foreach ($pages as $slug => $info) {
                $averageTime = $this->stopwatch->format($this->getAverageTime($info->attempts));
                $content .= '<tr>';
                $content .= '
                    <td><a href="' . $this->url[$environment] . '" target="_blank">' . $environment . '</a></td>
                    <td><a href="' . $this->url[$environment] . $slug . '" target="_blank">' . $slug . '</a></td>
                    <td>' . count($info->attempts) . '</td>
                    <td>' . $averageTime->min . 'min ' . $averageTime->sec . 'sec ' . $averageTime->ms . 'ms </td>                    
                ';
                $content .= '<td>';
                foreach ($info->attempts as $detail) {
                    $content .= $detail['time']->min . ' ' . $detail['time']->sec . ' ' . $detail['time']->ms . '<br>';
                }

                $content .= '</td>';
                $content .= '<td>' . $info->headers[0] . '</td>';

                if ($info->detect) {
                    $content .= '<td>ano</td>';
                } else
                {
                    $content .= '<td>ne</td>';
                }

                $content .= '</tr>';
            }
        }

        $content .= '</table>';

        echo $content;
        return $content;
    }

    /*
     * Run programme
     */
    public function run()
    {
        $this->makeBatch();
        $this->scan();
        $result = $this->show();
        $this->saveLog($result);
    }
}

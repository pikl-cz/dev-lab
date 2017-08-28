<?php

namespace devLab;
/*
 * TODO
 * - hezčí výpis složek - pro přehlednost od sebe odddělit jednotlivé složky (připravena fce makeBranch())
 */

echo "<pre>";
ini_set("xdebug.var_display_max_children", -1);
ini_set("xdebug.var_display_max_data", -1);
ini_set("xdebug.var_display_max_depth", -1);

class DevLab
{
    public $ignoreList, $root;
    private $tree;

    public function setRoot($root)
    {
        $this->root = $root;
        return $this->root;
    }

    public function setIgnoreList($toIgnore = array())
    {
        $this->ignoreList = $toIgnore;
        return $this->ignoreList;
    }

    private function scanFolder($folder = null)
    {
        $folder = scandir($folder);
        foreach ($this->ignoreList as $ignore)
        {
            unset($folder[array_search($ignore, $folder, true)]);
        }
        return $folder;
    }

    /*
    private function makeBranch($check)
    {
        $check = str_replace($this->root, '', $check);
        $explode = explode('/', $check);
        unset($explode[0]);
        return;
    }
    */

    private function buildTree($node = null)
    {
        if (!isset($node))
        {
            $node = $this->root;
        }
        $folder = $this->scanFolder($node);

        if (count($folder) < 1) {
            return;
        }

        foreach ($folder as $item) {
            $check = $node . '/' . $item;
            if (is_dir($check))
            {
                $this->buildTree($check);
            }

            if(is_file($check))
            {
                if (strpos($item, '.php'))
                {
                    $this->tree[$node][] = $item;
                }

                $shortPath = str_replace($this->root, '', $node) . '/' . $item;
                echo '<a href="' . $shortPath . '">' . $shortPath . '</a><br>';
            }
        }
    }

    public function run()
    {
        $this->buildTree();
    }
}

$list = new DevLab();
$list->setRoot(__DIR__);
$list->setIgnoreList(['.', '..', '.idea', '.git', '.gitignore', 'LICENSE', 'README.md', 'nbproject']);
$list->run();
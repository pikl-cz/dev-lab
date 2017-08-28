<?php

/*
 * TODO
 * - setFileTypesToInclude
 * - hezčí výpis složek - pro přehlednost od sebe odddělit jednotlivé složky (připravena fce makeBranch())
 */

namespace Assist;

class FileFolderTree
{
    public $ignoreList, $root;
    private $tree;

    /*
     * @param string $root First node of tree
     */
    function __construct($root = null)
    {
        if (isset($root))
        {
            $this->setRoot($root);
        }
        else
        {
            $this->setRoot(__DIR__);
        }
    }

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
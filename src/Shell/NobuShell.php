<?php

namespace App\Shell;

use Cake\ORM\TableRegistry;
use Cake\Console\Shell;
use Cake\Filesystem\Folder;

/**
 * Simple console wrapper around Psy\Shell.
 */
class NobuShell extends Shell
{

    public function main()
    {
        $lists = [
            'LB',
            'TU',
            'MF',
            'SN',
            'FF',
        ];
        $i = 1920;
        foreach ($lists as $list) {
            $folder = new Folder(ROOT . '/file/' . $list . '/');
            foreach ($folder->find() as $file) {
            
                copy(ROOT . '/file/' . $list . '/' . $file, ROOT . '/file/check/' . $i . 'L.png');
                $i++;
            }
        }
        $this->out('AAA');
    }

}

<?php

namespace App\Shell;

use Cake\ORM\TableRegistry;
use Cake\Console\Shell;

/**
 * Simple console wrapper around Psy\Shell.
 */
class VoiceIkoShell extends Shell
{

    public function main()
    {
        $BasePlayersTable = TableRegistry::get('BasePlayers');
        $basePlayers = $BasePlayersTable->find('all')->order('id');
        /*
        $baseIkoDir = ROOT . '/webroot/voice/member/';
        foreach($basePlayers as $basePlayer) {
            $baseFilePath = ROOT . '/webroot/voice/team/' . $basePlayer->team_ryaku_name . '/' . $basePlayer->no . '.wav';
            $baseFileShortPath = ROOT . '/webroot/voice/team/' . $basePlayer->team_ryaku_name . '/' . $basePlayer->no . 'd.wav';
        
            if (!file_exists($baseFilePath)) {
                // ファイルがなかった人リスト
                $this->out($basePlayer->team_ryaku_name);
                $this->out($basePlayer->name);
                $this->out($basePlayer->id);
            } else {
                exec('mkdir -p ' . $baseIkoDir . $basePlayer->id);
                copy($baseFilePath, $baseIkoDir . $basePlayer->id . '/base.wav');
                copy($baseFileShortPath, $baseIkoDir . $basePlayer->id . '/short.wav');
            }
        }
        */
        /*
        $baseIkoDir = ROOT . '/webroot/voice/member/';
        foreach($basePlayers as $basePlayer) {
            if (!is_dir($baseIkoDir . $basePlayer->id)) {
                $this->out($basePlayer->team_ryaku_name);
                $this->out($basePlayer->name);
                $this->out($basePlayer->id);
            }
        }
        */
        
        $baseIkoDir = ROOT . '/webroot/voice/member/';
        foreach($basePlayers as $basePlayer) {
            exec('mv ' . $baseIkoDir . $basePlayer->id . '/short.wav ' . $baseIkoDir . $basePlayer->id . '/full.wav');
        }
        
        
        debug('end');
        exit;
    }

}

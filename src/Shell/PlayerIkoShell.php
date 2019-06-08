<?php

namespace App\Shell;

use Cake\ORM\TableRegistry;
use Cake\Console\Shell;

/**
 * Simple console wrapper around Psy\Shell.
 */
class PlayerIkoShell extends Shell
{

    public function main()
    {
        $BasePlayersTable = TableRegistry::get('BasePlayers');
        $PlayersTable = TableRegistry::get('Players');
        $players = $PlayersTable->find()
            ->contain('Teams')
            ->order(['Players.id' => 'ASC']);

        $numberTeamList = [];

        foreach ($players as $player) {
            // teamを消してるやつはスルーでOK
            if (empty($player->team)) {
                continue;
            }
            if (empty($numberTeamList[$player->team->ryaku_name][$player->no])) {
                $basePlayer = $BasePlayersTable->newEntity([
                    'team_ryaku_name' => $player->team->ryaku_name,
                    'name' => $player->name,
                    'name_short' => $player->name_short,
                    'name_eng' => $player->name_eng,
                    'name_read' => $player->name_read,
                    'name_short_read' => $player->name_short_read,
                    'no' => $player->no,
                    'throw' => $player->throw,
                    'bat' => $player->bat,
                    'type_p' => $player->type_p,
                    'type_c' => $player->type_c,
                    'type_i' => $player->type_i,
                    'type_o' => $player->type_o,
                ]);
                $BasePlayersTable->save($basePlayer);
                if (!array_key_exists($player->team->ryaku_name, $numberTeamList)) {
                    $numberTeamList[$player->team->ryaku_name] = [];
                }
                $numberTeamList[$player->team->ryaku_name][$player->no] = $basePlayer;
                // 画像のディレクトリを作成して設置
                if (file_exists(ROOT . '/webroot/img/player/' . $player->team->ryaku_name . '/' . $player->no . '.jpg')) {
                    exec('mkdir -p ' . ROOT . '/webroot/img/base_player/' . $basePlayer->id . '/');
                    exec('cp ' . ROOT . '/webroot/img/player/' . $player->team->ryaku_name . '/' . $player->no . '.jpg ' . ROOT . '/webroot/img/base_player/' . $basePlayer->id . '/file');
                }
                if (file_exists(ROOT . '/webroot/img/player/' . $player->team->ryaku_name . '/' . $player->no . '.png')) {
                    exec('mkdir -p ' . ROOT . '/webroot/img/base_player/' . $basePlayer->id . '/');
                    exec('cp ' . ROOT . '/webroot/img/player/' . $player->team->ryaku_name . '/' . $player->no . '.png ' . ROOT . '/webroot/img/base_player/' . $basePlayer->id . '/file');
                }
            } else {
                $basePlayer = $BasePlayersTable->patchEntity($numberTeamList[$player->team->ryaku_name][$player->no] , [
                    'name' => $player->name,
                    'name_short' => $player->name_short,
                    'name_eng' => $player->name_eng,
                    'name_read' => $player->name_read,
                    'name_short_read' => $player->name_short_read,
                    'no' => $player->no,
                    'throw' => $player->throw,
                    'bat' => $player->bat,
                    'type_p' => $player->type_p,
                    'type_c' => $player->type_c,
                    'type_i' => $player->type_i,
                    'type_o' => $player->type_o,
                ]);
                $BasePlayersTable->save($basePlayer);
            }

            $player->base_player_id = $basePlayer->id;
            $PlayersTable->save($player);
        }
    }

}

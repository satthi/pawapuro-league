<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GameMembersFixture
 *
 */
class GameMembersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => 'id', 'precision' => null, 'unsigned' => null],
        'game_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => 'ゲームID', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'type' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => 'ホームチーム/ビジター', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'dajun' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '打順', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'position' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => 'ポジション', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'player_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '選手ID', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'stamen_flag' => ['type' => 'boolean', 'length' => null, 'default' => 0, 'null' => true, 'comment' => 'スタメンフラグ', 'precision' => null],
        'deleted' => ['type' => 'boolean', 'length' => null, 'default' => 0, 'null' => true, 'comment' => '削除フラグ', 'precision' => null],
        'deleted_date' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => '削除日時', 'precision' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => '登録日時', 'precision' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => '更新日時', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'game_id' => 1,
            'type' => 1,
            'dajun' => 1,
            'position' => 1,
            'player_id' => 1,
            'stamen_flag' => 1,
            'deleted' => 1,
            'deleted_date' => 1478703415,
            'created' => 1478703415,
            'modified' => 1478703415
        ],
    ];
}

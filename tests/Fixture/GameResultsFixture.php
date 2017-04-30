<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GameResultsFixture
 *
 */
class GameResultsFixture extends TestFixture
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
        'batter_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '打者', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'pitcher_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '投手', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'inning' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => 'イニング', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'result' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '結果', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'out_num' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => 'アウト数', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'display' => ['type' => 'text', 'length' => null, 'default' => null, 'null' => true, 'collate' => null, 'comment' => '表示', 'precision' => null],
        'stamen_flag' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => 'ヒット種別', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
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
            'batter_id' => 1,
            'pitcher_id' => 1,
            'inning' => 1,
            'result' => 1,
            'out_num' => 1,
            'display' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'stamen_flag' => 1,
            'deleted' => 1,
            'deleted_date' => 1478703422,
            'created' => 1478703422,
            'modified' => 1478703422
        ],
    ];
}

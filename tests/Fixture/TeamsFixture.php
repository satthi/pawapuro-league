<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TeamsFixture
 *
 */
class TeamsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => 'id', 'precision' => null, 'unsigned' => null],
        'season_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => 'シーズンID', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'name' => ['type' => 'text', 'length' => null, 'default' => null, 'null' => true, 'collate' => null, 'comment' => '表示名', 'precision' => null],
        'ryaku_name' => ['type' => 'text', 'length' => null, 'default' => null, 'null' => true, 'collate' => null, 'comment' => '省略', 'precision' => null],
        'game' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '試合', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'win' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '勝ち', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'lose' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '負け', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'draw' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '引き分け', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'deleted' => ['type' => 'boolean', 'length' => null, 'default' => 0, 'null' => true, 'comment' => '削除フラグ', 'precision' => null],
        'deleted_date' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => '削除日時', 'precision' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => '登録日時', 'precision' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => '更新日時', 'precision' => null],
        '_indexes' => [
            'teams_ryaku_name_idx' => ['type' => 'index', 'columns' => ['ryaku_name'], 'length' => []],
            'teams_win_idx' => ['type' => 'index', 'columns' => ['win'], 'length' => []],
            'teams_lose_idx' => ['type' => 'index', 'columns' => ['lose'], 'length' => []],
            'teams_draw_idx' => ['type' => 'index', 'columns' => ['draw'], 'length' => []],
        ],
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
            'season_id' => 1,
            'name' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'ryaku_name' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'game' => 1,
            'win' => 1,
            'lose' => 1,
            'draw' => 1,
            'deleted' => 1,
            'deleted_date' => 1478703449,
            'created' => 1478703449,
            'modified' => 1478703449
        ],
    ];
}

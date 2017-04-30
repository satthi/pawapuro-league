<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GamesFixture
 *
 */
class GamesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'season_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => 'シーズンID', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'date' => ['type' => 'date', 'length' => null, 'default' => null, 'null' => true, 'comment' => '日程', 'precision' => null],
        'home_team_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => 'ホームチーム', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'visitor_team_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => 'ビジターチーム', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'home_point' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => 'ホームチーム 得点', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'visitor_point' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => 'ビジターチーム 得点', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
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
            'season_id' => 1,
            'date' => '2016-11-10',
            'home_team_id' => 1,
            'visitor_team_id' => 1,
            'home_point' => 1,
            'visitor_point' => 1,
            'deleted' => 1,
            'deleted_date' => 1478793703,
            'created' => 1478793703,
            'modified' => 1478793703
        ],
    ];
}

<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * GameInningsFixture
 *
 */
class GameInningsFixture extends TestFixture
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
        'inning' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => 'イニング', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'omote_ura' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '表裏', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'hit' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => 'ヒット数', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'point' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '得点', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
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
            'inning' => 1,
            'omote_ura' => 1,
            'hit' => 1,
            'point' => 1,
            'deleted' => 1,
            'deleted_date' => 1478703406,
            'created' => 1478703406,
            'modified' => 1478703406
        ],
    ];
}

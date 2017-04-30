<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PlayersFixture
 *
 */
class PlayersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => 'id', 'precision' => null, 'unsigned' => null],
        'name' => ['type' => 'text', 'length' => null, 'default' => null, 'null' => true, 'collate' => null, 'comment' => 'プレイヤー名', 'precision' => null],
        'type' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => 'type', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'daseki' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '打席', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'dasu' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '打数', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'hit' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '安打', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'hr' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => 'HR', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'rbi' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '打点', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'inning' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '投球イニング', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'jiseki' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '自責点', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'win' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '勝ち', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'lose' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '負け', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'hold' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => 'ホールド', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'save' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => 'セーブ', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
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
            'name' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'type' => 1,
            'daseki' => 1,
            'dasu' => 1,
            'hit' => 1,
            'hr' => 1,
            'rbi' => 1,
            'inning' => 1,
            'jiseki' => 1,
            'win' => 1,
            'lose' => 1,
            'hold' => 1,
            'save' => 1,
            'deleted' => 1,
            'deleted_date' => 1478703445,
            'created' => 1478703445,
            'modified' => 1478703445
        ],
    ];
}

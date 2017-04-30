<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ResultsFixture
 *
 */
class ResultsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
        'name' => ['type' => 'text', 'length' => null, 'default' => null, 'null' => true, 'collate' => null, 'comment' => '表示名', 'precision' => null],
        'color_type' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '1:青 2:ピンク 3:黄色 4:グレー', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'position' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '配置', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'out' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => '標準のアウト数', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'dasu_flag' => ['type' => 'boolean', 'length' => null, 'default' => 0, 'null' => true, 'comment' => '打数に該当するか', 'precision' => null],
        'hit_flag' => ['type' => 'boolean', 'length' => null, 'default' => 0, 'null' => true, 'comment' => 'ヒットに該当するか', 'precision' => null],
        'base2_flag' => ['type' => 'boolean', 'length' => null, 'default' => 0, 'null' => true, 'comment' => '2ベースに該当するか', 'precision' => null],
        'base3_flag' => ['type' => 'boolean', 'length' => null, 'default' => 0, 'null' => true, 'comment' => '3ベースに該当するか', 'precision' => null],
        'hr_flag' => ['type' => 'boolean', 'length' => null, 'default' => 0, 'null' => true, 'comment' => 'HRに該当するか', 'precision' => null],
        'point_flag' => ['type' => 'boolean', 'length' => null, 'default' => 0, 'null' => true, 'comment' => '得点が確実に入るか', 'precision' => null],
        'sansin_flag' => ['type' => 'boolean', 'length' => null, 'default' => 0, 'null' => true, 'comment' => '三振に該当するか', 'precision' => null],
        'walk_flag' => ['type' => 'boolean', 'length' => null, 'default' => 0, 'null' => true, 'comment' => '四球に該当するか', 'precision' => null],
        'deadball_flag' => ['type' => 'boolean', 'length' => null, 'default' => 0, 'null' => true, 'comment' => '死球に該当するか', 'precision' => null],
        'bant_flag' => ['type' => 'boolean', 'length' => null, 'default' => 0, 'null' => true, 'comment' => '犠打に該当するか', 'precision' => null],
        'sacrifice_fly_flag' => ['type' => 'boolean', 'length' => null, 'default' => 0, 'null' => true, 'comment' => '犠飛に該当するか', 'precision' => null],
        'heisatsu_flag' => ['type' => 'boolean', 'length' => null, 'default' => 0, 'null' => true, 'comment' => '併殺に該当するか', 'precision' => null],
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
            'color_type' => 1,
            'position' => 1,
            'out' => 1,
            'dasu_flag' => 1,
            'hit_flag' => 1,
            'base2_flag' => 1,
            'base3_flag' => 1,
            'hr_flag' => 1,
            'point_flag' => 1,
            'sansin_flag' => 1,
            'walk_flag' => 1,
            'deadball_flag' => 1,
            'bant_flag' => 1,
            'sacrifice_fly_flag' => 1,
            'heisatsu_flag' => 1,
            'deleted' => 1,
            'deleted_date' => 1485612377,
            'created' => 1485612377,
            'modified' => 1485612377
        ],
    ];
}

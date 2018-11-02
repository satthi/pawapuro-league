<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AccidentsFixture
 *
 */
class AccidentsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => 'id', 'precision' => null, 'unsigned' => null],
        'player_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => true, 'comment' => 'player_id', 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
        'start_date' => ['type' => 'date', 'length' => null, 'default' => null, 'null' => true, 'comment' => 'start_date', 'precision' => null],
        'end_date' => ['type' => 'date', 'length' => null, 'default' => null, 'null' => true, 'comment' => 'end_date', 'precision' => null],
        'deleted' => ['type' => 'boolean', 'length' => null, 'default' => 0, 'null' => true, 'comment' => 'deleted', 'precision' => null],
        'deleted_date' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => 'deleted_date', 'precision' => null],
        'created' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => 'created', 'precision' => null],
        'modified' => ['type' => 'timestamp', 'length' => null, 'default' => null, 'null' => true, 'comment' => 'modified', 'precision' => null],
        '_indexes' => [
            'accidents_player_id_idx' => ['type' => 'index', 'columns' => ['player_id'], 'length' => []],
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
            'player_id' => 1,
            'start_date' => '2018-11-02',
            'end_date' => '2018-11-02',
            'deleted' => 1,
            'deleted_date' => 1541160472,
            'created' => 1541160472,
            'modified' => 1541160472
        ],
    ];
}

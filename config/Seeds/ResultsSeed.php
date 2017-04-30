<?php
use Migrations\AbstractSeed;

/**
 * Results seed.
 */
class ResultsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        // まずは三振・四球・死球
        $data[] = [
            'name' => '三振',
            'color_type' => 1,
            'position' => 0,
            'out' => 1,
            'dasu_flag' => true,
            'sansin_flag' => true
        ];
        $data[] = [
            'name' => '四球',
            'color_type' => 3,
            'out' => 0,
            'position' => 0,
            'walk_flag' => true
        ];
        $data[] = [
            'name' => '死球',
            'color_type' => 3,
            'out' => 0,
            'position' => 0,
            'deadball_flag' => true
        ];

		$positionLists = [
		    1 => '投',
		    2 => '捕',
		    3 => '一',
		    4 => '二',
		    5 => '三',
		    6 => '遊',
		    7 => '左',
		    8 => '中',
		    9 => '右',
		];

        // それ以外
		$baseSets = [
		    ['name' => 'ゴ','color_type' => 1, 'out' => 1, 'dasu_flag' => true],
		    ['name' => '併','color_type' => 1, 'out' => 2, 'dasu_flag' => true, 'heisatsu_flag' => true],
		    ['name' => '直','color_type' => 1, 'out' => 1, 'dasu_flag' => true],
		    ['name' => '飛','color_type' => 1, 'out' => 1, 'dasu_flag' => true],
		    ['name' => '失','color_type' => 4, 'out' => 0, 'dasu_flag' => true],
		    ['name' => 'バ','color_type' => 3, 'out' => 1, 'bant_flag' => true],
		    ['name' => 'バ失','color_type' => 3, 'out' => 0, 'bant_flag' => true],
		    ['name' => '犠飛','color_type' => 3, 'out' => 1, 'point_flag' => true, 'sacrifice_fly_flag' => true],
		    ['name' => '犠失','color_type' => 3, 'out' => 0, 'point_flag' => true, 'sacrifice_fly_flag' => true],
		    ['name' => 'FC','color_type' => 4, 'out' => 0, 'dasu_flag' => true],
		    ['name' => '安','color_type' => 2, 'out' => 0, 'dasu_flag' => true, 'hit_flag' => true],
		    ['name' => '２','color_type' => 2, 'out' => 0, 'dasu_flag' => true, 'hit_flag' => true, 'base2_flag' => true],
		    ['name' => '３','color_type' => 2, 'out' => 0, 'dasu_flag' => true, 'hit_flag' => true, 'base3_flag' => true],
		    ['name' => '走','color_type' => 2, 'out' => 0, 'dasu_flag' => true, 'hit_flag' => true, 'hr_flag' => true, 'point_flag' => true],
		    ['name' => '本','color_type' => 2, 'out' => 0, 'dasu_flag' => true, 'hit_flag' => true, 'hr_flag' => true, 'point_flag' => true],
		];
		
		foreach ($positionLists as $positionKey => $positionVal) {
			foreach ($baseSets as $baseSet) {
				$setData = $baseSet;
				$setData['name'] = $positionVal . $setData['name'];
				$setData['position'] = $positionKey;
				$data[] = $setData;
			}
		}

        $table = $this->table('results');
        $table->insert($data)->save();
    }
}

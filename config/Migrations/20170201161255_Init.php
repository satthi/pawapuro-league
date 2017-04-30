<?php
use Migrations\AbstractMigration;

class Init extends AbstractMigration
{
    public function up()
    {

        $this->table('game_innings')
            ->addColumn('game_id', 'integer', [
                'comment' => 'ゲームID',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('inning', 'integer', [
                'comment' => 'イニング(2で割る 奇数は表 偶数は裏)',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('hit', 'integer', [
                'comment' => 'ヒット数',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('point', 'integer', [
                'comment' => '得点',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('deleted', 'boolean', [
                'comment' => '削除フラグ',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('deleted_date', 'timestamp', [
                'comment' => '削除日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'timestamp', [
                'comment' => '登録日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'timestamp', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'game_id',
                ]
            )
            ->addIndex(
                [
                    'inning',
                ]
            )
            ->addIndex(
                [
                    'hit',
                ]
            )
            ->addIndex(
                [
                    'point',
                ]
            )
            ->create();

        $this->table('game_members')
            ->addColumn('game_id', 'integer', [
                'comment' => 'ゲームID',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('team_id', 'integer', [
                'comment' => 'チームID',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('dajun', 'integer', [
                'comment' => '打順',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('position', 'integer', [
                'comment' => 'ポジション',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('player_id', 'integer', [
                'comment' => '選手ID',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('stamen_flag', 'boolean', [
                'comment' => 'スタメンフラグ',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('deleted', 'boolean', [
                'comment' => '削除フラグ',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('deleted_date', 'timestamp', [
                'comment' => '削除日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'timestamp', [
                'comment' => '登録日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'timestamp', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'game_id',
                ]
            )
            ->addIndex(
                [
                    'team_id',
                ]
            )
            ->addIndex(
                [
                    'dajun',
                ]
            )
            ->addIndex(
                [
                    'player_id',
                ]
            )
            ->create();

        $this->table('game_pitcher_results')
            ->addColumn('game_id', 'integer', [
                'comment' => 'ゲームID',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('team_id', 'integer', [
                'comment' => 'チームID',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('pitcher_id', 'integer', [
                'comment' => 'ピッチャー',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('win', 'boolean', [
                'comment' => '勝ち',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('lose', 'boolean', [
                'comment' => '負け',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('save', 'boolean', [
                'comment' => 'セーブ',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('hold', 'boolean', [
                'comment' => 'ホールド',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('jiseki', 'integer', [
                'comment' => '自責点',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('deleted', 'boolean', [
                'comment' => '削除フラグ',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('deleted_date', 'timestamp', [
                'comment' => '削除日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'timestamp', [
                'comment' => '登録日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'timestamp', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('inning', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addIndex(
                [
                    'game_id',
                ]
            )
            ->addIndex(
                [
                    'team_id',
                ]
            )
            ->addIndex(
                [
                    'pitcher_id',
                ]
            )
            ->create();

        $this->table('game_results')
            ->addColumn('game_id', 'integer', [
                'comment' => 'ゲームID',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('team_id', 'integer', [
                'comment' => 'チームID',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('target_player_id', 'integer', [
                'comment' => '対象者(すべて)',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('type', 'integer', [
                'comment' => '種別 1:メンバー交代(スタメン含む) 2: 打席結果 3:盗塁 4:終了',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('dajun', 'integer', [
                'comment' => '打順(type 1のみ)',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('position', 'integer', [
                'comment' => 'ポジション(1-9以外 10:代打 11:代走)(type 1のみ)',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('pitcher_id', 'integer', [
                'comment' => '投手(type 2/3)',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('inning', 'integer', [
                'comment' => 'イニング(type1/2/3)(スタメン除く)',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('result_id', 'integer', [
                'comment' => '結果(固定でそれなりに大量に持つ)(type2/3)',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('out_num', 'integer', [
                'comment' => 'アウト数(type2/3)',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('hit_type', 'integer', [
                'comment' => 'ヒット種別(type2)',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('point', 'integer', [
                'comment' => '得点(type2/3)',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('deleted', 'boolean', [
                'comment' => '削除フラグ',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('deleted_date', 'timestamp', [
                'comment' => '削除日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'timestamp', [
                'comment' => '登録日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'timestamp', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'game_id',
                ]
            )
            ->addIndex(
                [
                    'team_id',
                ]
            )
            ->addIndex(
                [
                    'target_player_id',
                ]
            )
            ->addIndex(
                [
                    'dajun',
                ]
            )
            ->addIndex(
                [
                    'pitcher_id',
                ]
            )
            ->addIndex(
                [
                    'inning',
                ]
            )
            ->addIndex(
                [
                    'result_id',
                ]
            )
            ->addIndex(
                [
                    'out_num',
                ]
            )
            ->addIndex(
                [
                    'point',
                ]
            )
            ->create();

        $this->table('games')
            ->addColumn('season_id', 'integer', [
                'comment' => 'シーズンID',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('date', 'date', [
                'comment' => '日程',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('home_team_id', 'integer', [
                'comment' => 'ホームチーム',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('visitor_team_id', 'integer', [
                'comment' => 'ビジターチーム',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('home_point', 'integer', [
                'comment' => 'ホームチーム 得点',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('visitor_point', 'integer', [
                'comment' => 'ビジターチーム 得点',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('status', 'integer', [
                'comment' => '進捗 0:開始前 1 ~ 24イニング(2で割る 奇数は表 偶数は裏) 99:終了',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('out_num', 'integer', [
                'comment' => 'アウトカウント',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('win_pitcher_id', 'integer', [
                'comment' => '勝ち投手',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('lose_pitcher_id', 'integer', [
                'comment' => '負け投手',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('save_pitcher_id', 'integer', [
                'comment' => 'セーブ',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('deleted', 'boolean', [
                'comment' => '削除フラグ',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('deleted_date', 'timestamp', [
                'comment' => '削除日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'timestamp', [
                'comment' => '登録日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'timestamp', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'season_id',
                ]
            )
            ->addIndex(
                [
                    'home_team_id',
                ]
            )
            ->addIndex(
                [
                    'visitor_team_id',
                ]
            )
            ->addIndex(
                [
                    'status',
                ]
            )
            ->addIndex(
                [
                    'out_num',
                ]
            )
            ->addIndex(
                [
                    'win_pitcher_id',
                ]
            )
            ->addIndex(
                [
                    'lose_pitcher_id',
                ]
            )
            ->addIndex(
                [
                    'save_pitcher_id',
                ]
            )
            ->create();

        $this->table('players')
            ->addColumn('team_id', 'integer', [
                'comment' => 'チームID',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('name', 'text', [
                'comment' => 'プレイヤー名',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('name_short', 'text', [
                'comment' => '登録名',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('no', 'text', [
                'comment' => '背番号',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('throw', 'integer', [
                'comment' => '効き投げ',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('bat', 'integer', [
                'comment' => '効き打ち',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('type_p', 'integer', [
                'comment' => '守備位置 投手',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('type_c', 'integer', [
                'comment' => '守備位置 捕手',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('type_i', 'integer', [
                'comment' => '守備位置 内野手',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('type_o', 'integer', [
                'comment' => '守備位置 外野手',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('daseki', 'integer', [
                'comment' => '打席',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('dasu', 'integer', [
                'comment' => '打数',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('hit', 'integer', [
                'comment' => '安打',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('hr', 'integer', [
                'comment' => 'HR',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('rbi', 'integer', [
                'comment' => '打点',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('game', 'integer', [
                'comment' => '試合数',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('inning', 'integer', [
                'comment' => '投球イニング',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('jiseki', 'integer', [
                'comment' => '自責点',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('win', 'integer', [
                'comment' => '勝ち',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('lose', 'integer', [
                'comment' => '負け',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('hold', 'integer', [
                'comment' => 'ホールド',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('save', 'integer', [
                'comment' => 'セーブ',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('deleted', 'boolean', [
                'comment' => '削除フラグ',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('deleted_date', 'timestamp', [
                'comment' => '削除日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'timestamp', [
                'comment' => '登録日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'timestamp', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('sansin', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('steal', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('get_sansin', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('base2', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('base3', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('walk', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('deadball', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('heisatsu', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('bant', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('sacrifice_fly', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('p_dasu', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('p_hit', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('p_hr', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('avg', 'decimal', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('yashu_game', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('era', 'decimal', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('win_ratio', 'decimal', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('name_eng', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('name_read', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('name_short_read', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'team_id',
                ]
            )
            ->addIndex(
                [
                    'daseki',
                ]
            )
            ->addIndex(
                [
                    'dasu',
                ]
            )
            ->addIndex(
                [
                    'hit',
                ]
            )
            ->addIndex(
                [
                    'hr',
                ]
            )
            ->addIndex(
                [
                    'rbi',
                ]
            )
            ->addIndex(
                [
                    'game',
                ]
            )
            ->addIndex(
                [
                    'inning',
                ]
            )
            ->addIndex(
                [
                    'jiseki',
                ]
            )
            ->addIndex(
                [
                    'win',
                ]
            )
            ->addIndex(
                [
                    'lose',
                ]
            )
            ->addIndex(
                [
                    'hold',
                ]
            )
            ->addIndex(
                [
                    'save',
                ]
            )
            ->addIndex(
                [
                    'sansin',
                ]
            )
            ->addIndex(
                [
                    'steal',
                ]
            )
            ->addIndex(
                [
                    'get_sansin',
                ]
            )
            ->addIndex(
                [
                    'base2',
                ]
            )
            ->addIndex(
                [
                    'base3',
                ]
            )
            ->addIndex(
                [
                    'walk',
                ]
            )
            ->addIndex(
                [
                    'deadball',
                ]
            )
            ->addIndex(
                [
                    'heisatsu',
                ]
            )
            ->addIndex(
                [
                    'bant',
                ]
            )
            ->addIndex(
                [
                    'sacrifice_fly',
                ]
            )
            ->addIndex(
                [
                    'p_dasu',
                ]
            )
            ->addIndex(
                [
                    'p_hit',
                ]
            )
            ->addIndex(
                [
                    'p_hr',
                ]
            )
            ->addIndex(
                [
                    'avg',
                ]
            )
            ->addIndex(
                [
                    'yashu_game',
                ]
            )
            ->addIndex(
                [
                    'era',
                ]
            )
            ->addIndex(
                [
                    'win_ratio',
                ]
            )
            ->create();

        $this->table('results')
            ->addColumn('name', 'text', [
                'comment' => '表示名',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('color_type', 'integer', [
                'comment' => '1:青 2:ピンク 3:黄色 4:グレー',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('position', 'integer', [
                'comment' => '配置',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('out', 'integer', [
                'comment' => '標準のアウト数',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('dasu_flag', 'boolean', [
                'comment' => '打数に該当するか',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('hit_flag', 'boolean', [
                'comment' => 'ヒットに該当するか',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('base2_flag', 'boolean', [
                'comment' => '2ベースに該当するか',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('base3_flag', 'boolean', [
                'comment' => '3ベースに該当するか',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('hr_flag', 'boolean', [
                'comment' => 'HRに該当するか',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('point_flag', 'boolean', [
                'comment' => '得点が確実に入るか',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('sansin_flag', 'boolean', [
                'comment' => '三振に該当するか',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('walk_flag', 'boolean', [
                'comment' => '四球に該当するか',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('deadball_flag', 'boolean', [
                'comment' => '死球に該当するか',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('bant_flag', 'boolean', [
                'comment' => '犠打に該当するか',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('sacrifice_fly_flag', 'boolean', [
                'comment' => '犠飛に該当するか',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('heisatsu_flag', 'boolean', [
                'comment' => '併殺に該当するか',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('deleted', 'boolean', [
                'comment' => '削除フラグ',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('deleted_date', 'timestamp', [
                'comment' => '削除日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'timestamp', [
                'comment' => '登録日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'timestamp', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('seasons')
            ->addColumn('name', 'text', [
                'comment' => 'シーズン名',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('deleted', 'boolean', [
                'comment' => '削除フラグ',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('deleted_date', 'timestamp', [
                'comment' => '削除日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'timestamp', [
                'comment' => '登録日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'timestamp', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('teams')
            ->addColumn('season_id', 'integer', [
                'comment' => 'シーズンID',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('name', 'text', [
                'comment' => '表示名',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('ryaku_name', 'text', [
                'comment' => '省略',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('game', 'integer', [
                'comment' => '試合',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('win', 'integer', [
                'comment' => '勝ち',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('lose', 'integer', [
                'comment' => '負け',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('draw', 'integer', [
                'comment' => '引き分け',
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('deleted', 'boolean', [
                'comment' => '削除フラグ',
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('deleted_date', 'timestamp', [
                'comment' => '削除日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'timestamp', [
                'comment' => '登録日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'timestamp', [
                'comment' => '更新日時',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('remain', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('yomi', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('name_eng', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'ryaku_name',
                ]
            )
            ->addIndex(
                [
                    'win',
                ]
            )
            ->addIndex(
                [
                    'lose',
                ]
            )
            ->addIndex(
                [
                    'draw',
                ]
            )
            ->create();

        $this->table('v_month_batter_infos', ['id' => false, 'primary_key' => ['']])
            ->addColumn('year', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('month', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('player_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('team_ryaku_name', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('team_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('season_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('player_name', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('daseki', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('dasu', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('hit', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('hr', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('rbi', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->create();

        $this->table('v_month_pitcher_infos', ['id' => false, 'primary_key' => ['']])
            ->addColumn('season_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('team_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('team_ryaku_name', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('player_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('player_name', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('jiseki', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('game', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('win', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('lose', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('hold', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('save', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('year', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('month', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('inning', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->create();

        $this->table('v_month_team_infos', ['id' => false, 'primary_key' => ['']])
            ->addColumn('team_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('season_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('team_name', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('ryaku_team_name', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('year', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('month', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('game', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('win', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('lose', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('draw', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('point', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('loss', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('hr', 'decimal', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('hit', 'decimal', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('dasu', 'decimal', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('jiseki', 'decimal', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('inning', 'decimal', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('win_ratio', 'decimal', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('avg', 'decimal', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('era', 'decimal', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();
    }

    public function down()
    {
        $this->dropTable('game_innings');
        $this->dropTable('game_members');
        $this->dropTable('game_pitcher_results');
        $this->dropTable('game_results');
        $this->dropTable('games');
        $this->dropTable('players');
        $this->dropTable('results');
        $this->dropTable('seasons');
        $this->dropTable('teams');
        $this->dropTable('v_month_batter_infos');
        $this->dropTable('v_month_pitcher_infos');
        $this->dropTable('v_month_team_infos');
    }
}

<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BasePlayersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BasePlayersTable Test Case
 */
class BasePlayersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BasePlayersTable
     */
    public $BasePlayers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.base_players',
        'app.players',
        'app.game_members',
        'app.games',
        'app.seasons',
        'app.teams',
        'app.home_teams',
        'app.visitor_teams',
        'app.game_innings',
        'app.game_results',
        'app.results',
        'app.batters',
        'app.pitchers',
        'app.win_pitchers',
        'app.lose_pitchers',
        'app.save_pitchers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('BasePlayers') ? [] : ['className' => 'App\Model\Table\BasePlayersTable'];
        $this->BasePlayers = TableRegistry::get('BasePlayers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BasePlayers);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

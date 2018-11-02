<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AccidentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AccidentsTable Test Case
 */
class AccidentsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AccidentsTable
     */
    public $Accidents;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.accidents',
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
        'app.base_players',
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
        $config = TableRegistry::exists('Accidents') ? [] : ['className' => 'App\Model\Table\AccidentsTable'];
        $this->Accidents = TableRegistry::get('Accidents', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Accidents);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

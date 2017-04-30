<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GameResultsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GameResultsTable Test Case
 */
class GameResultsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\GameResultsTable
     */
    public $GameResults;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.game_results',
        'app.games',
        'app.batters',
        'app.pitchers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('GameResults') ? [] : ['className' => 'App\Model\Table\GameResultsTable'];
        $this->GameResults = TableRegistry::get('GameResults', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GameResults);

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

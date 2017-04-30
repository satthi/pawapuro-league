<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GameInningsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GameInningsTable Test Case
 */
class GameInningsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\GameInningsTable
     */
    public $GameInnings;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.game_innings',
        'app.games'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('GameInnings') ? [] : ['className' => 'App\Model\Table\GameInningsTable'];
        $this->GameInnings = TableRegistry::get('GameInnings', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GameInnings);

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

<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\GameMembersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\GameMembersTable Test Case
 */
class GameMembersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\GameMembersTable
     */
    public $GameMembers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.game_members',
        'app.games',
        'app.players'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('GameMembers') ? [] : ['className' => 'App\Model\Table\GameMembersTable'];
        $this->GameMembers = TableRegistry::get('GameMembers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->GameMembers);

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

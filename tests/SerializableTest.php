<?php

use Orchestra\Testbench\TestCase;
use LittleApps\SerializableModel\Serializable;
use Illuminate\Foundation\Testing\WithFaker;

class SerializableTest extends TestCase {
	use WithFaker;
	
	/**
     * Setup the test environment.
     */
    public function setUp() {
        parent::setUp();
        
		$this->loadLaravelMigrations(['--database' => 'testing']);
    }
	
	/**
	 * Tear down the test environment.
	 */
	public function tearDown() {
		parent::tearDown();
		
    }
	
	/**
     * Define environment setup.
     *
     * @param  Illuminate\Foundation\Application    $app
     * @return void
     */
    protected function getEnvironmentSetUp($app) {
        $app['config']->set('database.default', 'testing');
        
        $app->setBasePath(__DIR__);
	}
	
	protected function getOptionName() {
		return 'serializable';
	}
	
	protected function getRawOptionValue($name) {
		$option = DB::table('options')->where(compact('name'))->first();
		
		return $option->value;
	}
	
	public function testUsesSerializable() {
		$option = new Option;
		
		$this->assertContains(Serializable::class, class_uses($option));
	}
	
	/**
	 * Test a string is stored as is
	 * 
	 * @return void
	 */
	public function testStoresString() {
		$expected = $this->faker->text(30);
		
		$option = Option::create(['name' => $this->getOptionName(), 'value' => $expected]);
		
		$actualOption = Option::where('name', $this->getOptionName())->first();
		
		$this->assertNotNull($actualOption);
		$this->assertEquals($expected, $actualOption->value);
		$this->assertEquals($expected, $this->getRawOptionValue($this->getOptionName()));
	}
	
	/**
	* Tests an int is stored serialized.
	* 
	* @return void
	*/
	public function testStoresNumber() {
		$expectedNumber = $this->faker->randomNumber;
		$expectedNumberSerialized = serialize($expectedNumber);
		
		$option = Option::create(['name' => $this->getOptionName(), 'value' => $expectedNumber]);
		
		$actualOption = Option::where('name', $this->getOptionName())->first();
		
		$this->assertNotNull($actualOption);
		$this->assertEquals($expectedNumber, $actualOption->value);
		$this->assertEquals($expectedNumberSerialized, $this->getRawOptionValue($this->getOptionName()));
	}
	
	/**
	* Tests a float is stored serialized.
	* 
	* @return void
	*/
	public function testStoresFloat() {
		$expectedFloat = $this->faker->randomFloat;
		$expectedFloatSerialized = serialize($expectedFloat);
		
		$option = Option::create(['name' => $this->getOptionName(), 'value' => $expectedFloat]);
		
		$actualOption = Option::where('name', $this->getOptionName())->first();
		
		$this->assertNotNull($actualOption);
		$this->assertEquals($expectedFloat, $actualOption->value);
		$this->assertEquals($expectedFloatSerialized, $this->getRawOptionValue($this->getOptionName()));
	}
	
	/**
	* Tests a array is stored serialized.
	* 
	* @return void
	*/
	public function testStoresArray() {
		$expectedArray = $this->faker->randomElements(['John', 'Peter', 'Mark', 'Paul', 'Steven', 'Mary'], 3);
		$expectedArraySerialized = serialize($expectedArray);
		
		$option = Option::create(['name' => $this->getOptionName(), 'value' => $expectedArray]);
		
		$actualOption = Option::where('name', $this->getOptionName())->first();
		
		$this->assertNotNull($actualOption);
		$this->assertEquals($expectedArray, $actualOption->value);
		$this->assertEquals($expectedArraySerialized, $this->getRawOptionValue($this->getOptionName()));
	}
	
}
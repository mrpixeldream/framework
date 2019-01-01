<?php

declare(strict_types=1);

use DreamCodeFramework\Configuration\Concrete\FileLoader;
use DreamCodeFramework\Configuration\Manager;
use PHPUnit\Framework\TestCase;

/**
 * @TestCase
 */
class ConfigurationTest extends TestCase
{
    private $configurationManager;
    private $fileLoader;
    
    protected function setUp()
    {
        parent::setUp();
        
        $this->fileLoader = new FileLoader(__DIR__.'/../../framework-skeleton/config');
        
        $this->configurationManager = new Manager();
        $this->configurationManager->addLoader($this->fileLoader);
        $this->configurationManager->boot();
    }

    /**
     * @test
     */
    public function loader_loads_files_from_config_directory(): void 
    {
        $this->assertContains('app', $this->fileLoader->getRegisteredFiles());
    }
    
    /**
     * @test
     */
    public function loader_loads_files_from_subdirectories(): void
    {
        
    }

    /**
     * @test
     */
    public function existing_config_variables_resolve_to_value(): void
    {
        $this->assertEquals('debug', $this->configurationManager->get('app.mode'));
        $this->assertEquals('DreamCode Framework Skeleton App', $this->configurationManager->get('app.name'));
    }

    /**
     * @test
     */
    public function missing_configuration_variables_throw_exception(): void 
    {
        
    }
}

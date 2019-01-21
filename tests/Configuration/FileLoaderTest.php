<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use DreamCodeFramework\Configuration\Manager;
use DreamCodeFramework\Configuration\Concrete\FileLoader;
use DreamCodeFramework\Configuration\Exceptions\InvalidConfigurationKeyException;

/**
 * @TestCase
 */
class FileLoaderTest extends TestCase
{
    /** @var Manager */
    private $configurationManager;
    /** @var FileLoader */
    private $fileLoader;

    protected function setUp()
    {
        parent::setUp();

        $this->fileLoader = new FileLoader(__DIR__.'/../../../framework-skeleton/config');

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
    /*public function loader_loads_files_from_subdirectories(): void
    {

    }*/

    /**
     * @test
     */
    public function existing_config_variables_resolve_to_value(): void
    {
        $this->assertEquals('debug', $this->configurationManager->get('app.mode'));
        $this->assertEquals('DreamCode Framework Skeleton App', $this->configurationManager->get('app.name'));
        $this->assertEquals('Subkey Value', $this->configurationManager->get('app.sub.key'));
    }

    /**
     * @test
     */
    public function missing_configuration_variables_throw_exception(): void
    {
        $this->expectException(InvalidConfigurationKeyException::class);
        $this->configurationManager->get('key.not.exists');
    }
}

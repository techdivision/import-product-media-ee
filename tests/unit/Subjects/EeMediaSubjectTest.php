<?php

/**
 * TechDivision\Import\Product\Media\Ee\Subjects\EeMediaSubjectTest
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media-ee
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Media\Ee\Subjects;

use PHPUnit\Framework\TestCase;
use TechDivision\Import\Utils\CacheKeys;
use TechDivision\Import\Utils\RegistryKeys;
use TechDivision\Import\Utils\EntityTypeCodes;
use Doctrine\Common\Collections\ArrayCollection;
use TechDivision\Import\ExecutionContextInterface;
use TechDivision\Import\Configuration\PluginConfigurationInterface;
use TechDivision\Import\Configuration\SubjectConfigurationInterface;

/**
 * Test class for the media subject implementation for th Magento 2 EE.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media-ee
 * @link      http://www.techdivision.com
 */
class EeMediaSubjectTest extends TestCase
{

    /**
     * The subject we want to test.
     *
     * @var \TechDivision\Import\Product\Media\Ee\Subjects\EeMediaSubject
     */
    protected $subject;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     * @see \PHPUnit\Framework\TestCase::setUp()
     */
    protected function setUp()
    {

        // mock the execution context
        $mockExecutionContext = $this->getMockBuilder(ExecutionContextInterface::class)
            ->setMethods(get_class_methods(ExecutionContextInterface::class))
            ->getMock();
        $mockExecutionContext->expects($this->any())
            ->method('getEntityTypeCode')
            ->willReturn(EntityTypeCodes::CATALOG_PRODUCT);

        // mock the plugin configuration
        $mockPluginConfiguration = $this->getMockBuilder(PluginConfigurationInterface::class)
            ->setMethods(get_class_methods(PluginConfigurationInterface::class))
            ->getMock();
        $mockPluginConfiguration->expects($this->any())
            ->method('getExecutionContext')
            ->willReturn($mockExecutionContext);

        // load the mock configuration
        $mockConfiguration = $this->getMockBuilder(SubjectConfigurationInterface::class)
            ->setMethods(get_class_methods(SubjectConfigurationInterface::class))
            ->getMock();
        $mockConfiguration->expects($this->any())
            ->method('getPluginConfiguration')
            ->willReturn($mockPluginConfiguration);
        $mockConfiguration->expects($this->any())
            ->method('getCallbacks')
            ->willReturn(array());
        $mockConfiguration->expects($this->any())
            ->method('getObservers')
            ->willReturn(array());
        $mockConfiguration->expects($this->any())
            ->method('getHeaderMappings')
            ->willReturn(array());
        $mockConfiguration->expects($this->any())
            ->method('getImageTypes')
            ->willReturn(array());
        $mockConfiguration->expects($this->any())
            ->method('getFrontendInputCallbacks')
            ->willReturn(array());

        // create a mock registry processor
        $mockRegistryProcessor = $this->getMockBuilder('TechDivision\Import\Services\RegistryProcessorInterface')
            ->setMethods(get_class_methods('TechDivision\Import\Services\RegistryProcessorInterface'))
            ->getMock();

        // create a mock product media processor
        $mockProductMediaProcessor = $this->getMockBuilder('TechDivision\Import\Product\Services\ProductProcessorInterface')
            ->setMethods(get_class_methods('TechDivision\Import\Product\Services\ProductProcessorInterface'))
            ->getMock();

        // create a generator
        $mockGenerator = $this->getMockBuilder('TechDivision\Import\Utils\Generators\GeneratorInterface')
            ->setMethods(get_class_methods('TechDivision\Import\Utils\Generators\GeneratorInterface'))
            ->getMock();

        // mock the event emitter
        $mockEmitter = $this->getMockBuilder('League\Event\EmitterInterface')
                            ->setMethods(\get_class_methods('League\Event\EmitterInterface'))
                            ->getMock();

        // create the subject to be tested
        $this->subject = new EeMediaSubject(
            $mockRegistryProcessor,
            $mockGenerator,
            new ArrayCollection(),
            $mockEmitter,
            $mockProductMediaProcessor
        );

        // mock the filesytem
        $mockFilesystem = $this->getMockBuilder($filesystemInterface = 'TechDivision\Import\Adapter\FilesystemAdapterInterface')
                               ->setMethods(get_class_methods($filesystemInterface))
                               ->getMock();
        $mockFilesystem->expects($this->any())
                       ->method('isFile')
                       ->willReturn(true);

        // mock the configuration and the filesystem
        $this->subject->setConfiguration($mockConfiguration);
        $this->subject->setFilesystemAdapter($mockFilesystem);
    }

    /**
     * Test's the mapSkuToRowId() method successfull.
     *
     * @return void
     */
    public function testMapSkuToRowIdSuccessufull()
    {

        // initialize a mock status
        $status = array(
            RegistryKeys::SKU_ROW_ID_MAPPING => array($sku = 'TEST-01' => $rowId = 1000),
            RegistryKeys::SKU_ENTITY_ID_MAPPING => array(),
            RegistryKeys::GLOBAL_DATA => array(
                RegistryKeys::SKU_ENTITY_ID_MAPPING => array(),
                RegistryKeys::ATTRIBUTE_SETS => array(),
                RegistryKeys::STORE_WEBSITES => array(),
                RegistryKeys::EAV_ATTRIBUTES => array(),
                RegistryKeys::STORES => array(),
                RegistryKeys::LINK_TYPES => array(),
                RegistryKeys::IMAGE_TYPES => array(),
                RegistryKeys::LINK_ATTRIBUTES => array(),
                RegistryKeys::TAX_CLASSES => array(),
                RegistryKeys::CATEGORIES => array(),
                RegistryKeys::ROOT_CATEGORIES => array(),
                RegistryKeys::DEFAULT_STORE => array(),
                RegistryKeys::CORE_CONFIG_DATA => array(),
                RegistryKeys::ENTITY_TYPES => array(),
                RegistryKeys::EAV_USER_DEFINED_ATTRIBUTES => array(
                    EntityTypeCodes::CATALOG_PRODUCT => array()
                )
            )
        );

        // load a mock processor
        $this->subject->getRegistryProcessor()
                      ->expects($this->any())
                      ->method('getAttribute')
                      ->with(CacheKeys::STATUS)
                      ->willReturn($status);

        // inject and set-up the processor
        $this->subject->setUp(uniqid());

        // test the mapSkuToRowId() method
        $this->assertSame($rowId, $this->subject->mapSkuToRowId($sku));
    }
}

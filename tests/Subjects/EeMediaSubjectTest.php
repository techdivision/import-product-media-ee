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

use TechDivision\Import\Utils\RegistryKeys;
use TechDivision\Import\Product\Media\Utils\ConfigurationKeys;

/**
 * Test class for the media subject implementation for th Magento 2 EE.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media-ee
 * @link      http://www.techdivision.com
 */
class EeMediaSubjectTest extends \PHPUnit_Framework_TestCase
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
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp()
    {

        // mock the filesytem
        $mockFilesystem = $this->getMockBuilder($filesystemInterface = '\League\Flysystem\FilesystemInterface')
                               ->setMethods(get_class_methods($filesystemInterface))
                               ->getMock();
        $mockFilesystem->expects($this->any())
                       ->method('has')
                       ->willReturn(true);

        // mock the subject itself, because we need a mocked filesystem
        $this->subject = $this->getMockBuilder('TechDivision\Import\Product\Media\Ee\Subjects\EeMediaSubject')
                              ->setMethods(array('getFilesystem'))
                              ->getMock();
        $this->subject->expects($this->any())
                      ->method('getFilesystem')
                      ->willReturn($mockFilesystem);
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
                RegistryKeys::LINK_ATTRIBUTES => array(),
                RegistryKeys::TAX_CLASSES => array(),
                RegistryKeys::CATEGORIES => array(),
                RegistryKeys::ROOT_CATEGORIES => array(),
                RegistryKeys::DEFAULT_STORE => array(),
                RegistryKeys::CORE_CONFIG_DATA => array(),
            )
        );

        // load a mock processor
        $mockProcessor = $this->getMockBuilder($processorInterface = 'TechDivision\Import\Services\RegistryProcessorInterface')
                              ->setMethods(get_class_methods($processorInterface))
                              ->getMock();
        $mockProcessor->expects($this->any())
                      ->method('getAttribute')
                      ->willReturn($status);

        // load the mock main configuration
        $mockMainConfiguration = $this->getMockBuilder($subjectInterface = 'TechDivision\Import\ConfigurationInterface')
                                      ->setMethods(get_class_methods($subjectInterface))
                                      ->getMock();
        $mockMainConfiguration->expects($this->once())
                              ->method('getInstallationDir')
                              ->willReturn(__DIR__);

        // load the mock configuration
        $mockConfiguration = $this->getMockBuilder($subjectInterface = 'TechDivision\Import\Configuration\SubjectInterface')
                                  ->setMethods(get_class_methods($subjectInterface))
                                  ->getMock();
        $mockConfiguration->expects($this->once())
                          ->method('getConfiguration')
                          ->willReturn($mockMainConfiguration);
        $mockConfiguration->expects($this->exactly(3))
                          ->method('getParam')
                          ->withConsecutive(
                              array(ConfigurationKeys::ROOT_DIRECTORY),
                              array(ConfigurationKeys::MEDIA_DIRECTORY),
                              array(ConfigurationKeys::IMAGES_FILE__DIRECTORY)
                          )
                          ->willReturn(__DIR__, __DIR__, __DIR__);

        // inject and set-up the processor
        $this->subject->setRegistryProcessor($mockProcessor);
        $this->subject->setConfiguration($mockConfiguration);
        $this->subject->setUp();

        // test the mapSkuToRowId() method
        $this->assertSame($rowId, $this->subject->mapSkuToRowId($sku));
    }
}

<?php
/**
 * IMCakeHelperTest file
 *
 * Copyright (c) Atsushi Matsuo, Masayuki Nii
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Atsushi Matsuo, Masayuki Nii
 * @link          http://www.famlog.jp/imcake/
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('IMCakeHelper', 'IMCake.View/Helper');
App::uses('View', 'View');

/**
 * IMCakeHelperTest class
 */
class IMCakeHelperTest extends CakeTestCase {

/**
 * setUp method
 *
 * @return void
 */
    public function setUp() {
        parent::setUp();
        $this->View = new View(null);
    }

/**
 * tearDown method
 *
 * @return void
 */
    public function tearDown() {
        unset($this->View);
        parent::tearDown();
    }

/**
 * test pageConstruct()
 */
    public function testPageConstruct() {
    }

/**
 * test partialConstruct()
 */
    public function testPartialConstruct() {
    }

/**
 * test seekEnclosureNode()
 */
    public function testSeekEnclosureNode() {
    }

/**
 * test expandEnclosure()
 */
    public function testExpandEnclosure() {
    }

/**
 * test collectRepeatersOriginal()
 */
    public function testCollectRepeatersOriginal() {
    }

/**
 * test collectRepeaters()
 */
    public function testCollectRepeaters() {
    }

/**
 * test collectLinkedElement()
 */
    public function testCollectLinkedElement() {
    }

/**
 * test seekLinkedElement()
 */
    public function testSeekLinkedElement() {
    }

/**
 * test collectLinkDefinitions()
 */
    public function testCollectLinkDefinitions() {
    }

/**
 * test tableVoting()
 */
    public function testTableVoting() {
    }

/**
 * test getParentRepeater()
 */
    public function testGetParentRepeater() {
    }

/**
 * test getParentEnclosure()
 */
    public function testGetParentEnclosure() {
    }

/**
 * test isEnclosure()
 */
    public function testIsEnclosure() {
    }

/**
 * test isRepeater()
 */
    public function testIsRepeater() {
    }

/**
 * test searchLinkedElement()
 */
    public function testSearchLinkedElement() {
    }

/**
 * test isLinkedElement()
 */
    public function testIsLinkedElement() {
    }

/**
 * test getEnclosure()
 */
    public function testGetEnclosure() {
    }

/**
 * test isRepeaterOfEnclosure()
 */
    public function testIsRepeaterOfEnclosure() {
        $IMCake = new IMCakeHelper($this->View);
        
        $this->assertFalse($IMCake->isRepeaterOfEnclosure(NULL, NULL));
        
        $dom = new DOMDocument();
        $repeaterElement = $dom->createElement('tr');
        $enclosureElement = $dom->createElement('tbody');
        $this->assertTrue($IMCake->isRepeaterOfEnclosure($repeaterElement, $enclosureElement));

        $repeaterElement = $dom->createElement('option');
        $enclosureElement = $dom->createElement('select');
        $this->assertTrue($IMCake->isRepeaterOfEnclosure($repeaterElement, $enclosureElement));

        $repeaterElement = $dom->createElement('li');
        $enclosureElement = $dom->createElement('ol');
        $this->assertTrue($IMCake->isRepeaterOfEnclosure($repeaterElement, $enclosureElement));

        $repeaterElement = $dom->createElement('li');
        $enclosureElement = $dom->createElement('ul');
        $this->assertTrue($IMCake->isRepeaterOfEnclosure($repeaterElement, $enclosureElement));

        $repeaterElement = $dom->createElement('div');
        $repeaterElement->setAttribute('class', '_im_repeater');
        $enclosureElement = $dom->createElement('div');
        $enclosureElement->setAttribute('class', '_im_enclosure');
        $this->assertTrue($IMCake->isRepeaterOfEnclosure($repeaterElement, $enclosureElement));

        $repeaterElement = $dom->createElement('div');
        $repeaterElement->setAttribute('class', '_im_repeater');
        $enclosureElement = $dom->createElement('span');
        $enclosureElement->setAttribute('class', '_im_enclosure');
        $this->assertTrue($IMCake->isRepeaterOfEnclosure($repeaterElement, $enclosureElement));
        
        $repeaterElement = $dom->createElement('span');
        $repeaterElement->setAttribute('class', '_im_repeater');
        $enclosureElement = $dom->createElement('div');
        $enclosureElement->setAttribute('class', '_im_enclosure');
        $this->assertTrue($IMCake->isRepeaterOfEnclosure($repeaterElement, $enclosureElement));
        
        $repeaterElement = $dom->createElement('span');
        $repeaterElement->setAttribute('class', '_im_repeater');
        $enclosureElement = $dom->createElement('span');
        $enclosureElement->setAttribute('class', '_im_enclosure');
        $this->assertTrue($IMCake->isRepeaterOfEnclosure($repeaterElement, $enclosureElement));

        $repeaterElement = $dom->createElement('input');
        $repeaterElement->setAttribute('type', 'radio');
        $repeaterElement->setAttribute('class', '_im_repeater');
        $enclosureElement = $dom->createElement('div');
        $enclosureElement->setAttribute('class', '_im_enclosure');
        $this->assertTrue($IMCake->isRepeaterOfEnclosure($repeaterElement, $enclosureElement));

        $repeaterElement = $dom->createElement('input');
        $repeaterElement->setAttribute('type', 'check');
        $repeaterElement->setAttribute('class', '_im_repeater');
        $enclosureElement = $dom->createElement('div');
        $enclosureElement->setAttribute('class', '_im_enclosure');
        $this->assertTrue($IMCake->isRepeaterOfEnclosure($repeaterElement, $enclosureElement));

        $repeaterElement = $dom->createElement('input');
        $repeaterElement->setAttribute('class', '_im_repeater');
        $enclosureElement = $dom->createElement('div');
        $enclosureElement->setAttribute('class', '_im_enclosure');
        $this->assertFalse($IMCake->isRepeaterOfEnclosure($repeaterElement, $enclosureElement));
    }

/**
 * test getLinkedElementInfo()
 */
    public function testGetLinkedElementInfo() {
    }

/**
 * test resolveAlias()
 */
    public function testResolveAlias() {
    }

/**
 * test repeaterTagFromEncTag()
 */
    public function testRepeaterTagFromEncTag() {
        $IMCake = new IMCakeHelper($this->View);
        
        $this->assertEquals($IMCake->repeaterTagFromEncTag('tbody'), 'tr');
        $this->assertEquals($IMCake->repeaterTagFromEncTag('select'), 'option');
        $this->assertEquals($IMCake->repeaterTagFromEncTag('ul'), 'li');
        $this->assertEquals($IMCake->repeaterTagFromEncTag('ol'), 'li');
        $this->assertEquals($IMCake->repeaterTagFromEncTag('div'), 'div');
        $this->assertEquals($IMCake->repeaterTagFromEncTag('span'), 'span');
        $this->assertNULL($IMCake->repeaterTagFromEncTag(NULL));
    }

/**
 * test getNodeInfoArray()
 */
    public function testGetNodeInfoArray() {
        $IMCake = new IMCakeHelper($this->View);
        
        $nodeInfo = array('0' => 'fieldName');
        $this->assertInternalType('array', $IMCake->getNodeInfoArray($nodeInfo));
        $this->assertEquals($IMCake->getNodeInfoArray($nodeInfo), array('table' => '', 'field' => 'fieldName', 'target' => ''));
        
        $nodeInfo = array('0' => 'tableName', '1' => 'fieldName');
        $this->assertInternalType('array', $IMCake->getNodeInfoArray($nodeInfo));
        $this->assertEquals($IMCake->getNodeInfoArray($nodeInfo), array('table' => 'tableName', 'field' => 'fieldName', 'target' => ''));
        
        $nodeInfo = array('0' => 'tableName', '1' => 'fieldName', '2' => 'targetName');
        $this->assertInternalType('array', $IMCake->getNodeInfoArray($nodeInfo));
        $this->assertEquals($IMCake->getNodeInfoArray($nodeInfo), array('table' => 'tableName', 'field' => 'fieldName', 'target' => 'targetName'));

        $nodeInfo = 'fieldName';
        $this->assertInternalType('array', $IMCake->getNodeInfoArray($nodeInfo));
        $this->assertEquals($IMCake->getNodeInfoArray($nodeInfo), array('table' => '', 'field' => 'fieldName', 'target' => ''));

        $nodeInfo = 'tableName@fieldName';
        $this->assertInternalType('array', $IMCake->getNodeInfoArray($nodeInfo));
        $this->assertEquals($IMCake->getNodeInfoArray($nodeInfo), array('table' => 'tableName', 'field' => 'fieldName', 'target' => ''));

        $nodeInfo = 'tableName@fieldName@targetName';
        $this->assertInternalType('array', $IMCake->getNodeInfoArray($nodeInfo));
        $this->assertEquals($IMCake->getNodeInfoArray($nodeInfo), array('table' => 'tableName', 'field' => 'fieldName', 'target' => 'targetName'));
    }

/**
 * test getClassAttributeFromNode()
 */
    public function testGetClassAttributeFromNode() {
        $IMCake = new IMCakeHelper($this->View);
        
        $this->assertEquals($IMCake->getClassAttributeFromNode(NULL), '');
        
        $dom = new DOMDocument();
        $element = $dom->createElement('div');
        $element->setAttribute('class', 'testclass');
        $this->assertEquals($IMCake->getClassAttributeFromNode($element), 'testclass');
    }

/**
 * test cloneEveryNodes()
 */
    public function testCloneEveryNodes() {
    }

/**
 * test setDataToElement()
 */
    public function testSetDataToElement() {
    }

}
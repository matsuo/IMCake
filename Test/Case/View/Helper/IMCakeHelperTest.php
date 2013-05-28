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
        $IMCake = new IMCakeHelper($this->View);
        
        $this->assertEquals($IMCake->collectRepeaters(array()), array());
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
        $IMCake = new IMCakeHelper($this->View);
        
        $this->assertEquals($IMCake->collectLinkDefinitions(array()), array());
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
        $IMCake = new IMCakeHelper($this->View);
        
        $this->assertNull($IMCake->getParentRepeater(NULL));
    }

/**
 * test getParentEnclosure()
 */
    public function testGetParentEnclosure() {
        $IMCake = new IMCakeHelper($this->View);
        
        $this->assertNull($IMCake->getParentEnclosure(NULL));
    }

/**
 * test isEnclosure()
 */
    public function testIsEnclosure() {
        $IMCake = new IMCakeHelper($this->View);
        
        $this->assertFalse($IMCake->isEnclosure(NULL, TRUE));
    }

/**
 * test isRepeater()
 */
    public function testIsRepeater() {
        $IMCake = new IMCakeHelper($this->View);
        
        $this->assertFalse($IMCake->isRepeater(NULL, TRUE));
        
        $dom = new DOMDocument();
        $element = $dom->createElement('tr');
        $this->assertTrue($IMCake->isRepeater($element, TRUE));
        
        $element = $dom->createElement('li');
        $this->assertTrue($IMCake->isRepeater($element, TRUE));

        $element = $dom->createElement('option');
        $this->assertTrue($IMCake->isRepeater($element, TRUE));

        $element = $dom->createElement('div');
        $this->assertTrue($IMCake->isRepeater($element, TRUE));

        $element = $dom->createElement('span');
        $this->assertTrue($IMCake->isRepeater($element, TRUE));

        $element = $dom->createElement('table');
        $this->assertFalse($IMCake->isRepeater($element, TRUE));
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
        $IMCake = new IMCakeHelper($this->View);
        
        $this->assertFalse($IMCake->isLinkedElement(NULL));
        
        $dom = new DOMDocument();
        $element = $dom->createElement('div');
        $element->setAttribute('title', 'testtitle');
        $this->assertTrue($IMCake->isLinkedElement($element));

        $element = $dom->createElement('div');
        $element->setAttribute('class', 'IM[testfield]');
        $this->assertTrue($IMCake->isLinkedElement($element));

        $element = $dom->createElement('div');
        $element->setAttribute('class', 'testclass');
        $this->assertFalse($IMCake->isLinkedElement($element));
    }

/**
 * test getEnclosure()
 */
    public function testGetEnclosure() {
        $IMCake = new IMCakeHelper($this->View);
        
        $this->assertNull($IMCake->getEnclosure(NULL));
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
        $IMCake = new IMCakeHelper($this->View);
        
        $this->assertFalse($IMCake->getLinkedElementInfo(NULL));
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
        $this->assertNull($IMCake->repeaterTagFromEncTag(NULL));
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
        $IMCake = new IMCakeHelper($this->View);
        
        $this->assertEquals($IMCake->cloneEveryNodes(array()), array());
    }

/**
 * test setDataToElement()
 */
    public function testSetDataToElement() {
        $IMCake = new IMCakeHelper($this->View);
        
        $dom = new DOMDocument();
        $element = $dom->createElement('input');
        $element->setAttribute('type', 'checkbox');
        $element->setAttribute('value', 'testvalue');
        $IMCake->setDataToElement($element, 'innerHTML', 'testvalue');
        $this->assertEquals($element->getAttribute('checked'), 'checked');

        $element = $dom->createElement('input');
        $element->setAttribute('type', 'radio');
        $element->setAttribute('value', 'testvalue');
        $IMCake->setDataToElement($element, 'innerHTML', 'testvalue');
        $this->assertEquals($element->getAttribute('checked'), 'checked');
        
        $element = $dom->createElement('input');
        $element->setAttribute('type', 'checkbox');
        $element->setAttribute('value', 'testvalue');
        $IMCake->setDataToElement($element, 'innerHTML', 'testvalue2');
        $this->assertEquals($element->getAttribute('checked'), '');

        $element = $dom->createElement('input');
        $element->setAttribute('type', 'radio');
        $element->setAttribute('value', 'testvalue');
        $IMCake->setDataToElement($element, 'innerHTML', 'testvalue2');
        $this->assertEquals($element->getAttribute('checked'), '');

        $element = $dom->createElement('input');
        $element->setAttribute('type', 'text');
        $element->setAttribute('value', 'testvalue');
        $IMCake->setDataToElement($element, 'innerHTML', 'testvalue');
        $this->assertEquals($element->getAttribute('value'), 'testvalue');

        $element = $dom->createElement('input');
        $element->setAttribute('type', 'password');
        $element->setAttribute('value', 'testvalue');
        $IMCake->setDataToElement($element, 'innerHTML', 'testvalue');
        $this->assertEquals($element->getAttribute('value'), 'testvalue');

        $element = $dom->createElement('input');
        $element->setAttribute('type', 'hidden');
        $element->setAttribute('value', 'testvalue');
        $IMCake->setDataToElement($element, 'innerHTML', 'testvalue');
        $this->assertEquals($element->getAttribute('value'), 'testvalue');

        $element = $dom->createElement('textarea');
        $IMCake->setDataToElement($element, 'innerHTML', '');
        $this->assertEquals($element->childNodes->item(0)->nodeValue, '');

        $element = $dom->createElement('textarea');
        $IMCake->setDataToElement($element, 'innerHTML', 'testvalue');
        $this->assertEquals($element->childNodes->item(0)->nodeValue, 'testvalue');
    }

}
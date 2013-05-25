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
    }

/**
 * test getClassAttributeFromNode()
 */
    public function testGetClassAttributeFromNode() {
        $IMCake = new IMCakeHelper($this->View);
        $this->assertEquals($IMCake->getClassAttributeFromNode(NULL), '');
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
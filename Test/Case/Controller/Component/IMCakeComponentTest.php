<?php
/**
 * IMCakeComponentTest file
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

App::uses('Controller', 'Controller');
App::uses('Component', 'Controller');
App::uses('IMCakeComponent', 'IMCake.Controller/Component');


class IMCakeComponentTestController extends Controller {

/**
 * components property
 *
 * @var array
 */
    public $components = array('IMCake.IMCake');

}

class IMCakeComponentTest extends CakeTestCase {

    public $Controller;
    
/**
 * start
 *
 * @return void
 */
    public function setUp() {
        parent::setUp();
        $this->Controller = new IMCakeComponentTestController(new CakeRequest(), new CakeResponse());
        $this->Controller->constructClasses();
        $this->IMCake = $this->Controller->IMCake;
    }
    
/**
 * test properties
 */
    public function testProperties() {
        $this->assertFalse($this->Controller->autoRender);
        $this->assertFalse($this->Controller->autoLayout);
    }

}
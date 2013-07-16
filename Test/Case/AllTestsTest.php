<?php
/**
 * AllTests file
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
/**
 * AllTests class
 *
 * This test group will run all tests.
 */
class AllTests extends PHPUnit_Framework_TestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('All Tests');
        
        $basePath = App::pluginPath('IMCake') . 'Test' . DS . 'Case' . DS;
        
        $suite->addTestFile($basePath . 'View' . DS . 'Helper' . DS . 'IMCakeHelperTest.php');
        return $suite;
    }
}
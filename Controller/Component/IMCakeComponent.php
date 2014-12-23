<?php
/**
 * IMCake Component
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

class IMCakeComponent extends Component
{

    public function __construct(ComponentCollection $collection, $settings = array())
    {
        $this->Controller = $collection->getController();
        parent::__construct($collection, $settings);
    }

    public function render($id="")
    {
        $viewClass = $this->Controller->viewClass;
        if ($this->Controller->viewClass != 'View') {
            list($plugin, $viewClass) = pluginSplit($viewClass, TRUE);
            $viewClass = $viewClass . 'View';
            App::uses($viewClass, $plugin . 'View');
        }
        
        $View = new $viewClass($this->Controller);
        
        App::import('Helper', 'IMCake.IMCake');
        $helper = new IMCakeHelper($View);
        
        echo $helper->pageConstruct($this->Controller->modelClass, $View->render(), $id);
    }

}
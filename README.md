# IMCake [![Build Status](https://travis-ci.org/matsuo/IMCake.png?branch=master)](https://travis-ci.org/matsuo/IMCake) [![Coverage Status](https://coveralls.io/repos/matsuo/IMCake/badge.png?branch=master)](https://coveralls.io/r/matsuo/IMCake) [![Code Climate](https://codeclimate.com/github/matsuo/IMCake/badges/gpa.svg)](https://codeclimate.com/github/matsuo/IMCake)

IMCake is a plugin for CakePHP 2.x to work with INTER-Mediator using models of CakePHP.

To get more familiar with INTER-Mediator visit: http://inter-mediator.org/

## Notice

This product should be considered a work in progress, please test and use at your own risk and contribute back any changes or fixes you may have.

## Installation

1. Install IMCake.

		cd /path/to/root/app/Plugin
		git clone git://github.com/matsuo/IMCake.git IMCake

2. Add the following line to /path/to/root/app/Config/bootstrap.php to load this plugin.

		CakePlugin::load('IMCake');

3. Write your model file.

4. Write your view file. (This view file is compatible with a page file of INTER-Mediator.)

5. Add the following line to your controller file to use this plugin, for example.

		public $components = array('IMCake.IMCake');

6. Write the following line in your action method.

		$this->IMCake->render($id);

## Example

1. Controller Example

		<?php

		App::uses('AppController', 'Controller');

		class PeopleController extends AppController
		{

		    public $components = array('IMCake.IMCake');

		    function view($id="") {
		        $this->autoLayout = false;
				$this->autoRender = false;
				$this->IMCake->render($id);
		    }

		}

## Requirements

* CakePHP >= 2.3
* PHP >= 5.3

## Supported Database

* MySQL >= 5.5

## Credit

* Author: Atsushi Matsuo

## Thanks

Thanks to [Masayuki Nii](http://msyk.net/) (an author of "INTER-Mediator").

## MIT License

Copyright (c) 2013-2014 Atsushi Matsuo, Masayuki Nii

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
of the Software, and to permit persons to whom the Software is furnished to do
so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
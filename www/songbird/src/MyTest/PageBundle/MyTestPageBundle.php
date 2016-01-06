<?php

namespace MyTest\PageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MyTestPageBundle extends Bundle
{
	// use a child bundle
	public function getParent()
    {
		return 'BpehNestablePageBundle';
    }
}

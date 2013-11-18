<?php

namespace GbCreation\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GbCreationUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}

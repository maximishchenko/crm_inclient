<?php

class MyCDbFixtureManager extends CDbFixtureManager
{
	protected $_fixtureLoaded = null;

	public function prepare()
	{
		echo '11|';
		parent::prepare();
	}
} 
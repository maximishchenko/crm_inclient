<?php

/**
 * This is the model class for table "appearance_links".
 *
 * The followings are the available columns in table 'appearance_links':
 * @property integer $id
 * @property string $link_name
 * @property string $link_value
 */
class AppearanceLinks extends MainAppearanceLinks
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'appearance_links';
	}
}

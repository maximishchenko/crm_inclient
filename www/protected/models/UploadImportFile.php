<?php

/**
 * This is the model class for table "steps_type".
 *
 * The followings are the available columns in table 'steps_type':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Steps[] $steps
 */
class UploadImportFile extends CFormModel
{
    public $fileLoad;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [];
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return array(
            'file'=>'File',
        );
    }
}

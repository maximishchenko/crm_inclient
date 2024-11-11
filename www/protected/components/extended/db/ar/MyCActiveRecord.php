<?php

class MyCActiveRecord extends CActiveRecord
{
	/**
	 * Creates an active record instance.
	 * This method is called by {@link populateRecord} and {@link populateRecords}.
	 * You may override this method if the instance being created
	 * depends the attributes that are to be populated to the record.
	 * For example, by creating a record based on the value of a column,
	 * you may implement the so-called single-table inheritance mapping.
	 *
	 * @param array $attributes list of attribute values for the active records.
	 *
	 * @return CActiveRecord the active record
	 */
	protected function instantiate($attributes)
	{
		$classMain = get_class($this);
		$model = new $classMain(null);
		$classTrade = $attributes['type'] . 'TradeServer';
		$classQuote = $attributes['type'] . 'TradeServer';
		$settings = json_decode($attributes['settings'], true);
		$model->trade = new $classTrade($settings['tradeServer']);
		$model->quote = new $classQuote($settings['quoteServer']);

		return $model;
	}
}
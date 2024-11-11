<?php
class MyCDbCriteria extends CDbCriteria
{
	/**
	 * Appends a condition for matching the given list of column values.
	 * The generated condition will be concatenated to the existing {@link condition}
	 * via the specified operator which defaults to 'AND'.
	 * The search condition is generated using the SQL LIKE operator with the given column name and search keyword.
	 *
	 * @param array $columns list of column names and values to be matched
	 * @param array $value the search keyword.
	 * @param string $columnOperator the operator to concatenate multiple column matching condition. Defaults to 'AND'.
	 * @param string $operator the operator used to concatenate the new condition with the existing one.
	 * Defaults to 'AND'.
	 *
	 * @return CDbCriteria the criteria object itself
	 */
	public function addColumnSearchCondition($columns, $value, $columnOperator = 'AND', $operator = 'AND')
	{
		if (empty($value) || is_null($value))
			return false;
		$params = array();
		foreach ($columns as $name) {
			$params[] = $name . ' LIKE ' . self::PARAM_PREFIX . self::$paramCount;
			$this->params[self::PARAM_PREFIX . self::$paramCount++] = '%' . $value . '%';
		}

		return $this->addCondition(implode(" $columnOperator ", $params), $operator);
	}
}
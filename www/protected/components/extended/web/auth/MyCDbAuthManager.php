<?php
class MyCDbAuthManager extends CDbAuthManager
{
	/**
	 * Assigns an authorization item to a user.
	 * @param string $itemName the item name
	 * @param mixed $userId the user ID (see {@link IWebUser::getId})
	 * @param string $bizRule the business rule to be executed when {@link checkAccess} is called
	 * for this particular authorization item.
	 * @param mixed $data additional data associated with this assignment
	 * @return CAuthAssignment the authorization assignment information.
	 * @throws CException if the item does not exist or if the item has already been assigned to the user
	 */
	public function assign($itemName, $userId, $bizRule = null, $data = null)
	{
		if ($this->usingSqlite() && $this->getAuthItem($itemName) === null)
			throw new CException(Yii::t('yii', 'The item "{name}" does not exist.', array('{name}' => $itemName)));

		$this->db->createCommand()
			->insert($this->assignmentTable, array(
				'itemname' => $itemName,
				'user_id' => $userId,
				'bizrule' => $bizRule,
				'data' => serialize($data)
			));
		return new CAuthAssignment($this, $itemName, $userId, $bizRule, $data);
	}

	/**
	 * Returns the item assignment information.
	 * @param string $itemName the item name
	 * @param mixed $userId the user ID (see {@link IWebUser::getId})
	 * @return CAuthAssignment the item assignment information. Null is returned if
	 * the item is not assigned to the user.
	 */
	public function getAuthAssignment($itemName, $userId)
	{
		$row = $this->db->createCommand()
			->select()
			->from($this->assignmentTable)
			->where('itemname=:itemname AND user_id=:user_id', array(
				':itemname' => $itemName,
				':user_id' => $userId))
			->queryRow();
		if ($row !== false) {
			if (($data = @unserialize($row['data'])) === false)
				$data = null;
			return new CAuthAssignment($this, $row['itemname'], $row['user_id'], $row['bizrule'], $data);
		} else
			return null;
	}

	/**
	 * Returns the item assignments for the specified user.
	 * @param mixed $userId the user ID (see {@link IWebUser::getId})
	 * @return array the item assignment information for the user. An empty array will be
	 * returned if there is no item assigned to the user.
	 */
	public function getAuthAssignments($userId)
	{
		$rows = $this->db->createCommand()
			->select()
			->from($this->assignmentTable)
			->where('user_id=:user_id', array(':user_id' => $userId))
			->queryAll();
		$assignments = array();
		foreach ($rows as $row) {
			if (($data = @unserialize($row['data'])) === false)
				$data = null;
			$assignments[$row['itemname']] = new CAuthAssignment($this, $row['itemname'], $row['user_id'], $row['bizrule'], $data);
		}
		return $assignments;
	}

	/**
	 * Returns the authorization items of the specific type and user.
	 * @param integer $type the item type (0: operation, 1: task, 2: role). Defaults to null,
	 * meaning returning all items regardless of their type.
	 * @param mixed $userId the user ID. Defaults to null, meaning returning all items even if
	 * they are not assigned to a user.
	 * @return array the authorization items of the specific type.
	 */
	public function getAuthItems($type = null, $userId = null)
	{
		if ($type === null && $userId === null) {
			$command = $this->db->createCommand()
				->select()
				->from($this->itemTable);
		} else if ($userId === null) {
			$command = $this->db->createCommand()
				->select()
				->from($this->itemTable)
				->where('type=:type', array(':type' => $type));
		} else if ($type === null) {
			$command = $this->db->createCommand()
				->select('name,type,description,t1.bizrule,t1.data')
				->from(array(
					$this->itemTable . ' t1',
					$this->assignmentTable . ' t2'
				))
				->where('name=itemname AND user_id=:user_id', array(':user_id' => $userId));
		} else {
			$command = $this->db->createCommand()
				->select('name,type,description,t1.bizrule,t1.data')
				->from(array(
					$this->itemTable . ' t1',
					$this->assignmentTable . ' t2'
				))
				->where('name=itemname AND type=:type AND user_id=:user_id', array(
					':type' => $type,
					':user_id' => $userId
				));
		}
		$items = array();
		foreach ($command->queryAll() as $row) {
			if (($data = @unserialize($row['data'])) === false)
				$data = null;
			$items[$row['name']] = new CAuthItem($this, $row['name'], $row['type'], $row['description'], $row['bizrule'], $data);
		}
		return $items;
	}

	/**
	 * Returns a value indicating whether the item has been assigned to the user.
	 * @param string $itemName the item name
	 * @param mixed $userId the user ID (see {@link IWebUser::getId})
	 * @return boolean whether the item has been assigned to the user.
	 */
	public function isAssigned($itemName, $userId)
	{
		return $this->db->createCommand()
			->select('itemname')
			->from($this->assignmentTable)
			->where('itemname=:itemname AND user_id=:user_id', array(
				':itemname' => $itemName,
				':user_id' => $userId))
			->queryScalar() !== false;
	}

	/**
	 * Revokes an authorization assignment from a user.
	 * @param string $itemName the item name
	 * @param mixed $userId the user ID (see {@link IWebUser::getId})
	 * @return boolean whether removal is successful
	 */
	public function revoke($itemName, $userId)
	{
		return $this->db->createCommand()
			->delete($this->assignmentTable, 'itemname=:itemname AND user_id=:user_id', array(
				':itemname' => $itemName,
				':user_id' => $userId
			)) > 0;
	}

	/**
	 * Saves the changes to an authorization assignment.
	 * @param CAuthAssignment $assignment the assignment that has been changed.
	 */
	public function saveAuthAssignment($assignment)
	{
		$this->db->createCommand()
			->update($this->assignmentTable, array(
				'bizrule' => $assignment->getBizRule(),
				'data' => serialize($assignment->getData()),
			), 'itemname=:itemname AND user_id=:user_id', array(
				'itemname' => $assignment->getItemName(),
				'user_id' => $assignment->getUserId()
			));
	}
}
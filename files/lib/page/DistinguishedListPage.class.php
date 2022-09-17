<?php
namespace wcf\page;
use wcf\data\user\DistinguishedUserProfileList;
use wcf\system\WCF;

/**
 * Shows page which distinguished members.
 * 
 * @author		2018-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.distinguished
 */
class DistinguishedListPage extends SortablePage {
	/**
	 * @inheritDoc
	 */
	public $neededPermissions = ['user.profile.canViewDistinguishedList'];
	
	/**
	 * @inheritDoc
	 */
	public $itemsPerPage = USER_DISTINGUISHED_COUNT;
	
	/**
	 * @inheritDoc
	 */
	public $defaultSortField = '';
	
	/**
	 * @inheritDoc
	 */
	public $defaultSortOrder = 'DESC';
	
	/**
	 * @inheritDoc
	 */
	public $validSortFields = [];
	
	/**
	 * @inheritDoc
	 */
	public $objectListClassName = DistinguishedUserProfileList::class;
	
	/**
	 * userID condition value
	 */
	public $userIDs = [];
	
	/**
	 * @inheritDoc
	 */
	protected function initObjectList() {
		parent::initObjectList();
		
		// exclude groups
		if (!empty(USER_DISTINGUISHED_EXCLUDE_GROUPS)) {
			$groupIDs = explode(',', USER_DISTINGUISHED_EXCLUDE_GROUPS);
			$this->objectList->getConditionBuilder()->add('user_table.userID NOT IN (SELECT userID FROM wcf'.WCF_N.'_user_to_group WHERE groupID IN (?))', [$groupIDs]);
		}
		
		// exclude banned / disabled
		if (USER_DISTINGUISHED_EXCLUDE_BANNED) {
			$this->objectList->getConditionBuilder()->add('user_table.banned = ?', [0]);
		}
		if (USER_DISTINGUISHED_EXCLUDE_DISABLED) {
			$this->objectList->getConditionBuilder()->add('user_table.activationCode = ?', [0]);
		}
		
		// last activity
		if (USER_DISTINGUISHED_LAST_ACTIVITY) {
			$this->objectList->getConditionBuilder()->add('user_table.lastActivityTime > ?', [TIME_NOW - USER_DISTINGUISHED_LAST_ACTIVITY * 86400]);
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public function readData() {
		// sort fields
		if (!empty(USER_DISTINGUISHED_OPTIONS)) {
			$this->validSortFields = explode(',', USER_DISTINGUISHED_OPTIONS);
			$this->defaultSortField = $this->validSortFields[0];
		}
		
		parent::readData();
	}
	
	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign([
				'sortFields' => $this->validSortFields,
				'controllerObject' => null
		]);
	}
}

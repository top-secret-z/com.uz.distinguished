<?php
namespace wcf\system\option;
use wcf\data\option\Option;
use wcf\data\user\group\UserGroup;
use wcf\system\exception\UserInputException;
use wcf\system\option\user\group\UserGroupsUserGroupOptionType;
use wcf\util\ArrayUtil;
use wcf\util\StringUtil;

/**
 * Distinguished group option type implementation for a user group select list.
 * 
 * @author		2018-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.distinguished
 */
class DistinguishedGroupOptionType extends UserGroupsUserGroupOptionType {
	/**
	 * @inheritDoc
	 */
	public function getFormElement(Option $option, $value) {
		// get selected group
		$selectedGroups = explode(',', $value);
		
		// get all groups except everyone, guests and registered
		$groups = UserGroup::getGroupsByType([], [1, 2, 3]);
		
		// generate html
		$html = '';
		foreach ($groups as $group) {
			$html .= '<label><input type="checkbox" name="values['.StringUtil::encodeHTML($option->optionName).'][]" value="'.$group->groupID.'"'.(in_array($group->groupID, $selectedGroups) ? ' checked' : '').'> '.$group->getName().'</label>';
		}
		
		return $html;
	}
	
	/**
	 * @inheritDoc
	 */
	public function validate(Option $option, $newValue) {
		// get all groups except everyone, guests and registered
		$groups = UserGroup::getGroupsByType([], [1, 2, 3]);
		
		// get new value
		if (!is_array($newValue)) $newValue = [];
		$selectedGroups = ArrayUtil::toIntegerArray($newValue);
		
		// check groups
		foreach ($selectedGroups as $groupID) {
			if (!isset($groups[$groupID])) {
				throw new UserInputException($option->optionName, 'validationFailed');
			}
		}
	}
}

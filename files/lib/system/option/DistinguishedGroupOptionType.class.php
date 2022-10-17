<?php

/*
 * Copyright by Udo Zaydowicz.
 * Modified by SoftCreatR.dev.
 *
 * License: http://opensource.org/licenses/lgpl-license.php
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program; if not, write to the Free Software Foundation,
 * Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
namespace wcf\system\option;

use wcf\data\option\Option;
use wcf\data\user\group\UserGroup;
use wcf\system\exception\UserInputException;
use wcf\system\option\user\group\UserGroupsUserGroupOptionType;
use wcf\util\ArrayUtil;
use wcf\util\StringUtil;

/**
 * Distinguished group option type implementation for a user group select list.
 */
class DistinguishedGroupOptionType extends UserGroupsUserGroupOptionType
{
    /**
     * @inheritDoc
     */
    public function getFormElement(Option $option, $value)
    {
        // get selected group
        $selectedGroups = \explode(',', $value);

        // get all groups except everyone, guests and registered
        $groups = UserGroup::getGroupsByType([], [1, 2, 3]);

        // generate html
        $html = '';
        foreach ($groups as $group) {
            $html .= '<label><input type="checkbox" name="values[' . StringUtil::encodeHTML($option->optionName) . '][]" value="' . $group->groupID . '"' . (\in_array($group->groupID, $selectedGroups) ? ' checked' : '') . '> ' . $group->getName() . '</label>';
        }

        return $html;
    }

    /**
     * @inheritDoc
     */
    public function validate(Option $option, $newValue)
    {
        // get all groups except everyone, guests and registered
        $groups = UserGroup::getGroupsByType([], [1, 2, 3]);

        // get new value
        if (!\is_array($newValue)) {
            $newValue = [];
        }
        $selectedGroups = ArrayUtil::toIntegerArray($newValue);

        // check groups
        foreach ($selectedGroups as $groupID) {
            if (!isset($groups[$groupID])) {
                throw new UserInputException($option->optionName, 'validationFailed');
            }
        }
    }
}

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
namespace wcf\page;

use wcf\data\user\DistinguishedUserProfileList;
use wcf\system\WCF;

/**
 * Shows page which distinguished members.
 */
class DistinguishedListPage extends SortablePage
{
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
    protected function initObjectList()
    {
        parent::initObjectList();

        // exclude groups
        if (!empty(USER_DISTINGUISHED_EXCLUDE_GROUPS)) {
            $groupIDs = \explode(',', USER_DISTINGUISHED_EXCLUDE_GROUPS);
            $this->objectList->getConditionBuilder()->add('user_table.userID NOT IN (SELECT userID FROM wcf' . WCF_N . '_user_to_group WHERE groupID IN (?))', [$groupIDs]);
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
    public function readData()
    {
        // sort fields
        if (!empty(USER_DISTINGUISHED_OPTIONS)) {
            $this->validSortFields = \explode(',', USER_DISTINGUISHED_OPTIONS);
            $this->defaultSortField = $this->validSortFields[0];
        }

        parent::readData();
    }

    /**
     * @inheritDoc
     */
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'sortFields' => $this->validSortFields,
            'controllerObject' => null,
        ]);
    }
}

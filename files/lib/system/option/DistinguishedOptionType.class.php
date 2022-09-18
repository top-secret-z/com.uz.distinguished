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

use wcf\data\distinguished\Distinguished;
use wcf\data\option\Option;
use wcf\system\WCF;

/**
 * Option type implementation for distinguished option selection.
 */
class DistinguishedOptionType extends AbstractOptionType
{
    /**
     * list of available options
     */
    protected static $options;

    /**
     * @inheritDoc
     */
    public function validate(Option $option, $newValue)
    {
        if (!\is_array($newValue)) {
            $newValue = [];
        }

        foreach ($newValue as $optionName) {
            if (!\in_array($optionName, self::getOptions())) {
                throw new UserInputException($option->optionName, 'validationFailed');
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function getData(Option $option, $newValue)
    {
        if (!\is_array($newValue)) {
            return '';
        }

        return \implode(',', $newValue);
    }

    /**
     * @inheritDoc
     */
    public function getFormElement(Option $option, $value)
    {
        $options = self::getOptions();
        if ($option->issortable && $value) {
            $sortedOptions = \explode(',', $value);

            // remove old options
            $sortedOptions = \array_intersect($sortedOptions, $options);

            // append the non-checked options after the checked and sorted options
            $options = \array_merge($sortedOptions, \array_diff($options, $sortedOptions));
        }

        WCF::getTPL()->assign([
            'option' => $option,
            'value' => \explode(',', $value),
            'availableOptions' => $options,
        ]);

        return WCF::getTPL()->fetch('distinguishedOptionType');
    }

    /**
     * Returns the list of available options.
     */
    protected static function getOptions()
    {
        if (self::$options === null) {
            self::$options = [];
            $sql = "SELECT    optionName
                    FROM    wcf" . WCF_N . "_distinguished";
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute();
            //self::$options = $statement->fetchAll(\PDO::FETCH_COLUMN);
            while ($option = $statement->fetchColumn()) {
                if ($option == 'articles' && !MODULE_ARTICLE) {
                    continue;
                }
                if ($option == 'distAttachments' && !MODULE_ATTACHMENT) {
                    continue;
                }
                if ($option == 'likesReceived' && !MODULE_LIKE) {
                    continue;
                }
                if ($option == 'trophyPoints' && !MODULE_TROPHY) {
                    continue;
                }

                self::$options[] = $option;
            }
        }

        return self::$options;
    }
}

<?php
namespace wcf\data\distinguished;
use wcf\data\DatabaseObjectList;

/**
 * Represents a list of distinguished display options.
 * 
 * @author		2018-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.distinguished
 */
class DistinguishedList extends DatabaseObjectList {
	/**
	 * @inheritDoc
	 */
	public $className = Distinguished::class;
}

<?php
namespace wcf\data\distinguished;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\WCF;

/**
 * Executes distinguished display option-related actions.
 * 
 * @author		2018-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.distinguished
 */
class DistinguishedAction extends AbstractDatabaseObjectAction {
	/**
	 * @inheritDoc
	 */
	protected $className = DistinguishedEditor::class;
	
	/**
	 * @inheritDoc
	 */
	protected $permissionsUpdate = ['admin.user.canManageUserOption'];
	protected $permissionsCreate = ['admin.user.canManageUserOption'];
	protected $permissionsDelete = ['admin.user.canManageUserOption'];
	
	/**
	 * @inheritDoc
	 */
	protected $requireACP = ['create', 'delete', 'update'];
}

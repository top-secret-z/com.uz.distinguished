<?php
namespace wcf\system\package\plugin;
use wcf\data\distinguished\DistinguishedEditor;
use wcf\system\WCF;

/**
 * Installs, updates and deletes additional types.
 *  
 * @author		2018-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.distinguished
 */
class DistinguishedPackageInstallationPlugin extends AbstractXMLPackageInstallationPlugin {
	/**
	 * @inheritDoc
	 */
	public $className = DistinguishedEditor::class;
	
	/**
	 * @inheritDoc
	 */
	public $tableName = 'distinguished';
	
	/**
	 * @inheritDoc
	 */
	public $tagName = 'distinguished';
	
	/**
	 * @inheritDoc
	 */
	protected function handleDelete(array $items) {
		$sql = "DELETE FROM	wcf".WCF_N."_".$this->tableName."
				WHERE		optionName = ? AND packageID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		foreach ($items as $item) {
			$statement->execute([$item['attributes']['name'], $this->installation->getPackageID()
			]);
		}
	}
	
	/**
	 * @inheritDoc
	 */
	protected function prepareImport(array $data) {
		return [
				'optionName' => $data['attributes']['name'],
				'application' => $data['elements']['application'],
		];
	}
	
	/**
	 * @inheritDoc
	 */
	protected function findExistingItem(array $data) {
		$sql = "SELECT	*
				FROM	wcf".WCF_N."_".$this->tableName."
				WHERE	optionName = ? AND packageID = ?";
		$parameters = [
				$data['optionName'],
				$this->installation->getPackageID()
		];
		
		return [
				'sql' => $sql,
				'parameters' => $parameters
		];
	}
}

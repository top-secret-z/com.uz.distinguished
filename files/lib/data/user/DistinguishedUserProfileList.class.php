<?php
namespace wcf\data\user;
use wcf\system\WCF;

/**
 * Represents a list of distinguished user profiles.
 * 
 * @author		2018-2022 Zaydowicz
 * @license		GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package		com.uz.wcf.distinguished
 */
class DistinguishedUserProfileList extends UserList {
	/**
	 * @inheritDoc
	 */
	public $sqlOrderBy = 'user_table.username';
	
	/**
	 * @inheritDoc
	 */
	public $decoratorClassName = UserProfile::class;
	
	/**
	 * @inheritDoc
	 */
	public function __construct() {
		parent::__construct();
		
		if (!empty($this->sqlSelects)) $this->sqlSelects .= ',';
		$this->sqlSelects .= "user_avatar.*";
		$this->sqlJoins .= " LEFT JOIN wcf".WCF_N."_user_avatar user_avatar ON (user_avatar.avatarID = user_table.avatarID)";
		
		if (MODULE_USER_RANK) {
			$this->sqlSelects .= ",user_rank.*";
			$this->sqlJoins .= " LEFT JOIN wcf".WCF_N."_user_rank user_rank ON (user_rank.rankID = user_table.rankID)";
		}
		
		// get current location
		$this->sqlSelects .= ", session.pageID, session.pageObjectID, session.lastActivityTime AS sessionLastActivityTime";
		$this->sqlJoins .= " LEFT JOIN wcf".WCF_N."_session session ON (session.userID = user_table.userID)";
		
		// attachments and comments
		$this->sqlSelects .= ", attachment.distAttachments";
		$this->sqlJoins .= " LEFT JOIN (SELECT userID, COUNT(*) AS distAttachments FROM wcf".WCF_N."_attachment GROUP BY userID) attachment ON (attachment.userID = user_table.userID)";
		
		$this->sqlSelects .= ", comment.distComments";
		$this->sqlJoins .= " LEFT JOIN (SELECT userID, COUNT(*) AS distComments FROM wcf".WCF_N."_comment GROUP BY userID) comment ON (comment.userID = user_table.userID)";
		
		// follower
		$this->sqlSelects .= ", follow.distFollowers";
		$this->sqlJoins .= " LEFT JOIN (SELECT followUserID, COUNT(*) AS distFollowers FROM wcf".WCF_N."_user_follow GROUP BY followUserID) follow ON (follow.followUserID = user_table.userID)";
	}
}

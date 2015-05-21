<?php
/**
*
* @package Log Searches Extension
* @copyright (c) 2015 david63
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace david63\logsearches\acp;

class logsearches_module
{
	public $u_action;

	function main($id, $mode)
	{
		global $phpbb_container, $user;

		$this->tpl_name		= 'logsearches';
		$this->page_title	= $user->lang('SEARCH_LOG');

		// Get an instance of the admin controller
		$admin_controller = $phpbb_container->get('david63.logsearches.admin.controller');

		$admin_controller->display_options();
		$admin_controller->display_output();

		// Make the $u_action url available in the admin controller
		$admin_controller->set_page_url($this->u_action);
	}
}

<?php
/**
*
* @package Log Searches Extension
* @copyright (c) 2015 david63
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace david63\logsearches\acp;

class logsearches_info
{
	function module()
	{
		return array(
			'filename'	=> '\david63\logsearches\acp\logsearches_module',
			'title'		=> 'SEARCH_LOG',
			'modes'		=> array(
				'main'	=> array('title' => 'SEARCH_LOG', 'auth' => 'ext_david63/logsearches && acl_a_board', 'cat' => array('SEARCH_LOG')),
			),
		);
	}
}

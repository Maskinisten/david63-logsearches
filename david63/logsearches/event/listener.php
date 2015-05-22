<?php
/**
*
* @package Log Searches Extension
* @copyright (c) 2015 david63
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace david63\logsearches\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/**
	* The database table the search log is stored in
	*
	* @var string
	*/
	protected $search_log_table;

	/**
	* Constructor for listener
	*
	* @param \phpbb\config\config					$config				Config object
	* @param \phpbb\user                			$user				User object
	* @param \phpbb\db\driver\driver_interface		$db                 Database object
	* @param string									$search_log_table   Name of the table used to store board rules data
	*
	* @return \david63\logsearches\event\listener
	* @access public
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\user $user, \phpbb\db\driver\driver_interface $db, $search_log_table)
	{
		$this->config			= $config;
		$this->user				= $user;
		$this->db				= $db;
		$this->search_log_table	= $search_log_table;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.search_results_modify_search_title' => 'log_search',
		);
	}

	public function log_search($event)
	{
		if ($this->config['search_log_enable'])
		{
			$keywords			= $event['keywords'];
			$total_match_count	= ($event['total_match_count'] > 0) ? true : false;

			if (($this->config['search_log_all'] || (!$this->config['search_log_all'] && !$total_match_count)) && $keywords)
			{
				// Sets the values required for the log
				$sql_ary = array(
					'log_search_type'	=> $total_match_count,
					'user_id'			=> (int) $this->user->data['user_id'],
					'log_data'			=> $keywords,
					'log_ip'			=> $this->user->ip,
					'log_time'			=> time(),
				);

				// Insert the search data to the database
				$sql = 'INSERT INTO ' . $this->search_log_table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
				$this->db->sql_query($sql);
			}
		}
	}

}

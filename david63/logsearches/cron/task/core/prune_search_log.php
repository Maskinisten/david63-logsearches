<?php
/**
*
* @package Log Searches Extension
* @copyright (c) 2015 david63
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace david63\logsearches\cron\task\core;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

class prune_search_log extends \phpbb\cron\task\base
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\log\log */
	protected $log;

	/** @var \phpbb\user */
	protected $user;

	/**
	* The database table the search log is stored in
	*
	* @var string
	*/
	protected $search_log_table;

	/**
	* Constructor.
	*
	* @param phpbb_config		$config The config
	* @param phpbb_db_driver	$db The db connection
	* @param \phpbb\log\log		$log
	* @param \phpbb\user		$user
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\log\log $log, \phpbb\user $user, $search_log_table)
	{
		$this->config			= $config;
		$this->db				= $db;
		$this->log				= $log;
		$this->user				= $user;
		$this->search_log_table	= $search_log_table;
	}

	/**
	* Runs this cron task.
	*
	* @return null
	*/
	public function run()
	{
		if ($this->config['search_log_prune_days'] > 0)
		{
			$last_log = time() - ($this->config['search_log_prune_days'] * $this->config['search_log_prune_gc']);

			$and = ($this->config['search_log_prune_all']) ? '' : 'AND log_search_type <> 0';

			$sql = 'DELETE FROM ' . $this->search_log_table . '
				WHERE log_time < ' . $last_log . "
				$and";
			$this->db->sql_query($sql);

			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'SEARCH_LOG_PRUNE_LOG');

			$this->config->set('search_log_prune_last_gc', time(), true);
		}
	}

	/**
	* Returns whether this cron task can run, given current board configuration.
	*
	* @return bool
	*/
	public function is_runnable()
	{
		return (bool) $this->config['search_log_prune_days'] > 0;
	}

	/**
	* Returns whether this cron task should run now, because enough time
	* has passed since it was last run.
	*
	* @return bool
	*/
	public function should_run()
	{
		return $this->config['search_log_prune_days'] > 0 && time() > ($this->config['search_log_prune_last_gc'] + $this->config['search_log_prune_gc']);
	}
}

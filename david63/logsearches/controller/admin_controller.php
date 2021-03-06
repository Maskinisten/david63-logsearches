<?php
/**
*
* @package Log Searches Extension
* @copyright (c) 2015 david63
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace david63\logsearches\controller;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Admin controller
*/
class admin_controller implements admin_interface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var ContainerInterface */
	protected $container;

	/**
	* The database table the search log is stored in
	*
	* @var string
	*/
	protected $search_log_table;

	/** @var string Custom form action */
	protected $u_action;

	/**
	* Constructor for admin controller
	*
	* @param \phpbb\config\config				$config		Config object
	* @param \phpbb\db\driver\driver_interface	$db
	* @param \phpbb\request\request				$request	Request object
	* @param \phpbb\template\template			$template	Template object
	* @param \phpbb\user						$this->user		User object
	* @param ContainerInterface					$container	Service container interface
	* @param \phpbb\auth\auth $auth
	*
	* @return \phpbb\logsearches\controller\admin_controller
	* @access public
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\request\request $request, \phpbb\template\template $template, \phpbb\user $user, ContainerInterface $container, \phpbb\auth\auth $auth, $search_log_table)
	{
		$this->config		= $config;
		$this->db  			= $db;
		$this->request		= $request;
		$this->template		= $template;
		$this->user			= $user;
		$this->container	= $container;
		$this->auth			= $auth;
		$this->search_log_table	= $search_log_table;
	}

	/**
	* Display the options a user can configure for this extension
	*
	* @return null
	* @access public
	*/
	public function display_options()
	{
		// Create a form key for preventing CSRF attacks
		$form_key = 'searchlog';
		add_form_key($form_key);

		// Is the form being submitted
		if ($this->request->is_set_post('submit'))
		{
			// Is the submitted form is valid
			if (!check_form_key($form_key))
			{
				trigger_error($this->user->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			// If no errors, process the form data
			// Set the options the user configured
			$this->set_options();

			// Add option settings change action to the admin log
			$phpbb_log = $this->container->get('log');
			$phpbb_log->add('admin', $this->user->data['user_id'], $this->user->ip, 'SEARCH_LOG_LOG');

			// Option settings have been updated and logged
			// Confirm this to the user and provide link back to previous page
			trigger_error($this->user->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
		}

		$this->template->assign_vars(array(
			'SEARCH_LOG_ALL'		=> isset($this->config['search_log_all']) ? $this->config['search_log_all'] : '',
			'SEARCH_LOG_ENABLE'		=> isset($this->config['search_log_enable']) ? $this->config['search_log_enable'] : '',
			'SEARCH_LOG_PER_PAGE'	=> isset($this->config['search_log_per_page']) ? $this->config['search_log_per_page'] : '',
			'SEARCH_LOG_PRUNE_ALL'	=> isset($this->config['search_log_prune_all']) ? $this->config['search_log_prune_all'] : '',
			'SEARCH_LOG_PRUNE_DAYS'	=> isset($this->config['search_log_prune_days']) ? $this->config['search_log_prune_days'] : '',

			'U_ACTION'				=> $this->u_action,
		));
	}

	/**
	* Set the options a user can configure
	*
	* @return null
	* @access protected
	*/
	protected function set_options()
	{
		$this->config->set('search_log_all', $this->request->variable('search_log_all', ''));
		$this->config->set('search_log_enable', $this->request->variable('search_log_enable', ''));
		$this->config->set('search_log_per_page', $this->request->variable('search_log_per_page', ''));
		$this->config->set('search_log_prune_all', $this->request->variable('search_log_prune_all', 0));
		$this->config->set('search_log_prune_days', $this->request->variable('search_log_prune_days', 0));
	}

	/**
	* Display the output for this extension
	*
	* @return null
	* @access public
	*/
	public function display_output()
	{
		// Start initial var setup
		$action		= $this->request->variable('action', '');
		$deletemark = $this->request->variable('delmarked', false, false, \phpbb\request\request_interface::POST);
		$marked		= $this->request->variable('mark', array(0));
		$start		= $this->request->variable('start', 0);

		// Sort keys
		$sort_days	= $this->request->variable('st', 0);
		$sort_dir	= $this->request->variable('sd', 'd');
		$sort_key	= $this->request->variable('sk', 't');

		// Delete entries if requested and able
		if ($deletemark && $this->auth->acl_get('a_clearlogs'))
		{
			if (confirm_box(true))
			{
				$conditions = array();

				if ($deletemark && sizeof($marked))
				{
					$conditions['log_id'] = array('IN' => $marked);
				}

				$this->search_log_delete($conditions);
			}
			else
			{
				confirm_box(false, $this->user->lang['CONFIRM_OPERATION'], build_hidden_fields(array(
					'start'		=> $start,
					'delmarked'	=> $deletemark,
					'mark'		=> $marked,
					'st'		=> $sort_days,
					'sk'		=> $sort_key,
					'sd'		=> $sort_dir,
					'action'	=> $action,
				)));
			}
		}

		// Sorting
		$limit_days = array(0 => $this->user->lang['ALL_ENTRIES'], 1 => $this->user->lang['1_DAY'], 7 => $this->user->lang['7_DAYS'], 14 => $this->user->lang['2_WEEKS'], 30 => $this->user->lang['1_MONTH'], 90 => $this->user->lang['3_MONTHS'], 180 => $this->user->lang['6_MONTHS'], 365 => $this->user->lang['1_YEAR']);
		$sort_by_text = array('u' => $this->user->lang['SORT_USERNAME'], 't' => $this->user->lang['SORT_DATE'], 'i' => $this->user->lang['SORT_IP'], 'o' => $this->user->lang['SORT_KEYWORDS']);
		$sort_by_sql = array('u' => 'u.username_clean', 't' => 'l.log_time', 'i' => 'l.log_ip', 'o' => 'l.log_data');

		$s_limit_days = $s_sort_key = $s_sort_dir = $u_sort_param = '';
		gen_sort_selects($limit_days, $sort_by_text, $sort_days, $sort_key, $sort_dir, $s_limit_days, $s_sort_key, $s_sort_dir, $u_sort_param);

		// Define where and sort sql for use in displaying logs
		$sql_where	= ($sort_days) ? (time() - ($sort_days * 86400)) : 0;
		$sql_sort	= $sort_by_sql[$sort_key] . ' ' . (($sort_dir == 'd') ? 'DESC' : 'ASC');

		$log_count = 0;

		// Get total log count for pagination
		$sql = 'SELECT COUNT(log_id) AS total_logs
			FROM ' . $this->search_log_table . '
				WHERE log_time >= ' . (int) $sql_where;
		$result		= $this->db->sql_query($sql);
		$log_count	= (int) $this->db->sql_fetchfield('total_logs');
		$this->db->sql_freeresult($result);

		$action		= $this->u_action . "&amp;$u_sort_param";
		$pagination	= $this->container->get('pagination');
		$start		= $pagination->validate_start($start, $this->config['search_log_per_page'], $log_count);
		$pagination->generate_template_pagination($action, 'pagination', 'start', $log_count, $this->config['search_log_per_page'], $start);

		$sql = 'SELECT l.*, u.username, u.username_clean, u.user_colour
			FROM ' . $this->search_log_table . ' l, ' . USERS_TABLE . ' u
			WHERE u.user_id = l.user_id
			AND l.log_time >= ' . (int) $sql_where . "
			ORDER BY $sql_sort";
		$result = $this->db->sql_query_limit($sql, $this->config['search_log_per_page'], $start);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('log', array(
				'USERNAME'	=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
				'IP'		=> $row['log_ip'],
				'DATE'		=> $this->user->format_date($row['log_time']),
				'TYPE'		=> ($row['log_search_type']) ? $this->user->lang['SEARCH_SUCCESS'] : $this->user->lang['SEARCH_FAIL'],
				'DATA'		=> $row['log_data'],
				'ID'		=> $row['log_id'],
			));
		}
		$this->db->sql_freeresult($result);

		$this->template->assign_vars(array(
			'S_CLEARLOGS'	=> $this->auth->acl_get('a_clearlogs'),
			'S_LIMIT_DAYS'	=> $s_limit_days,
			'S_SHOW_TYPE'	=> $this->config['search_log_all'],
			'S_SORT_DIR'	=> $s_sort_dir,
			'S_SORT_KEY'	=> $s_sort_key,

			'U_ACTION'		=> $this->u_action . "&amp;$u_sort_param&amp;start=$start",
		));
	}

	protected function search_log_delete($conditions = array())
	{
		if (!sizeof($conditions))
		{
			trigger_error($this->user->lang('NO_LOG_ENTRIES_SELECTED') . adm_back_link($this->u_action));
		}

		$sql_where = 'WHERE ';

		if (isset($conditions['keywords']))
		{
			$sql_where .= $this->generate_sql_keyword($conditions['keywords'], '');

			unset($conditions['keywords']);
		}

		foreach ($conditions as $field => $field_value)
		{
			if (is_array($field_value) && sizeof($field_value) == 2 && !is_array($field_value[1]))
			{
				$sql_where .= $field . ' ' . $field_value[0] . ' ' . $field_value[1];
			}
			else if (is_array($field_value) && isset($field_value['IN']) && is_array($field_value['IN']))
			{
				$sql_where .= $this->db->sql_in_set($field, $field_value['IN']);
			}
			else
			{
				$sql_where .= $field . ' = ' . $field_value;
			}
		}

		$sql = 'DELETE FROM ' . $this->search_log_table . "
					$sql_where";
		$this->db->sql_query($sql);

		$phpbb_log = $this->container->get('log');
		$phpbb_log->add('admin', $this->user->data['user_id'], $this->user->ip, 'SEARCH_LOG_CLEAR');
	}

	/**
	* Set page url
	*
	* @param string $u_action Custom form action
	* @return null
	* @access public
	*/
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}
}

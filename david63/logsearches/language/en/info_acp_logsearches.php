<?php
/**
*
* @package Log Searches Extension
* @copyright (c) 2015 david63
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

/// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'SEARCH_LOG'					=> 'Search log',
	'SEARCH_LOG_EXPLAIN'			=> 'This lists the actions carried out by searches. This log provides you with information you are able to use for solving specific search problems. You can sort by username, date, IP or keyword. If you have appropriate permissions you can also clear individual operations or the log as a whole.',
	'SEARCH_LOG_OPTIONS'			=> 'Search log options',
	'SEARCH_LOG_OPTIONS_EXPLAIN'	=> 'Set the options for the search log.',
	'SEARCH_LOG_ENABLE'				=> 'Enable the logging of searches',
	'SEARCH_LOG_ALL'				=> 'Log all searches',
	'SEARCH_LOG_ALL_EXPLAIN'		=> 'Setting this to No will only log searches with no results.',
	'SEARCH_LOG_PRUNE_ALL'			=> 'Prune all search log entries',
	'SEARCH_LOG_PRUNE_ALL_EXPLAIN'	=> 'Setting this to No will only prune found search log entries.',
	'SEARCH_LOG_PRUNE_DAYS'			=> 'Prune log file days',
	'SEARCH_LOG_PRUNE_DAYS_EXPLAIN'	=> 'The number of days to leave entries in the search log.<br />Setting this to zero will disable the pruning of the log file.',
	'SEARCH_LOG_LOG'				=> '<strong>Search log options updated</strong>',
	'SEARCH_LOG_PRUNE_LOG'			=> '<strong>Search log file pruned</strong>',
	'SEARCH_SUCCESS'				=> 'Success',
	'SEARCH_FAIL'					=> 'Fail',
));

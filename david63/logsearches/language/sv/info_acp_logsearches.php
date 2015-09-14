<?php
/**
*
* @package Log Searches Extension
* @copyright (c) 2015 david63
* @translation by Holger (https://www.maskinisten.net)
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
	'NO_LOG_ENTRIES_SELECTED'		=> 'Inga loggposter har valts',

	'SEARCH_ALL'					=> 'Alla',
	'SEARCH_FAIL'					=> 'Utan träffar',
	'SEARCH_FAILED'					=> 'Utan träffar',
	'SEARCH_KEYWORDS'				=> 'Sökt(a) nyckelord',
	'SEARCH_LOG'					=> 'Söklogg',
	'SEARCH_LOG_ALL'				=> 'Logga alla sökningar',
	'SEARCH_LOG_ALL_EXPLAIN'		=> 'Ställs detta in till "Utan träffar" kommer endast sökningar som ej levererat träffar att loggas.',
	'SEARCH_LOG_CLEAR'				=> '<strong>Sökloggen har rensats</strong>',
	'SEARCH_LOG_ENABLE'				=> 'Aktivera loggning av sökningar',
	'SEARCH_LOG_EXPLAIN'			=> 'Detta listar upp handlingar som utförts under sökning. Denna logg ger dig information som du kan använda till att lösa speciella problem. Du kan sortera efter användarnamn, datum, IP eller sökord. Om du har motsvarande behörighet kan du även radera individuella loggposter.',
	'SEARCH_LOG_LOG'				=> '<strong>Söklogg-inställningarna har aktualiserats</strong>',
	'SEARCH_LOG_OPTIONS'			=> 'Söklogg-inställningar',
	'SEARCH_LOG_OPTIONS_EXPLAIN'	=> 'Ändra inställningarna för sökloggen.',
	'SEARCH_LOG_PER_PAGE'			=> 'Loggposter per sida',
	'SEARCH_LOG_PER_PAGE_EXPLAIN'	=> 'Ställ in antalet loggposter per sida.',
	'SEARCH_LOG_PRUNE_ALL'			=> 'Rensa alla sökloggposter',
	'SEARCH_LOG_PRUNE_ALL_EXPLAIN'	=> 'Ställs detta in till "Med träffar" kommer alla sökloggposter MED träffar att rensas och endast loggposter UTAN träffar sparas.',
	'SEARCH_LOG_PRUNE_DAYS'			=> 'Rensningsintervall i dagar',
	'SEARCH_LOG_PRUNE_DAYS_EXPLAIN'	=> 'Antal dagar loggposterna skall sparas.<br />Ställ in detta till noll för att deaktivera rensningen av sökloggen.',
	'SEARCH_LOG_PRUNE_LOG'			=> '<strong>Sökloggen har rensats</strong>',
	'SEARCH_RESULT'					=> 'Resultat',
	'SEARCH_SUCCESS'				=> 'Framgångsrikt',
	'SEARCH_SUCCESSFUL'				=> 'Med träffar',

	'SORT_KEYWORDS'					=> 'Nyckelord',
));

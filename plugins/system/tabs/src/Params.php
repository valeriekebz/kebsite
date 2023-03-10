<?php
/**
 * @package         Tabs
 * @version         7.1.6
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2017 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace RegularLabs\Plugin\System\Tabs;

defined('_JEXEC') or die;

use JFactory;
use RegularLabs\Library\Parameters as RL_Parameters;
use RegularLabs\Library\PluginTag as RL_PluginTag;
use RegularLabs\Library\RegEx as RL_RegEx;
use RegularLabs\Library\Uri as RL_Uri;

class Params
{
	protected static $params  = null;
	protected static $regexes = null;

	public static function get()
	{
		if ( ! is_null(self::$params))
		{
			return self::$params;
		}

		$params = RL_Parameters::getInstance()->getPluginParams('tabs');

		$params->tag_open  = RL_PluginTag::clean($params->tag_open);
		$params->tag_close = RL_PluginTag::clean($params->tag_close);

		$params->tag_link = isset($params->tag_link) ? $params->tag_link : 'tablink';
		$params->tag_link = RL_PluginTag::clean($params->tag_link);

		$params->use_responsive_view = false;

		self::$params = $params;

		return self::$params;
	}

	public static function getTags($only_start_tags = false)
	{
		$params = self::get();

		list($tag_start, $tag_end) = self::getTagCharacters();

		$tags = [
			[
				$tag_start . $params->tag_open,
				$tag_start . $params->tag_link,
			],
			[
				$tag_start . '/' . $params->tag_close . $tag_end,
				$tag_start . '/' . $params->tag_link . $tag_end,
			],
		];

		return $only_start_tags ? $tags['0'] : $tags;
	}

	public static function getAlignment()
	{
		$params = self::get();


		if ( ! $params->alignment)
		{
			$params->alignment = JFactory::getLanguage()->isRTL() ? 'right' : 'left';
		}

		return 'align_' . $params->alignment;
	}

	public static function getPositioning()
	{


		return 'top';
	}

	public static function getRegex($type = 'tag')
	{
		$regexes = self::getRegexes();

		return isset($regexes->{$type}) ? $regexes->{$type} : $regexes->tag;
	}

	private static function getRegexes()
	{
		if ( ! is_null(self::$regexes))
		{
			return self::$regexes;
		}

		$params = self::get();

		// Tag character start and end
		list($tag_start, $tag_end) = self::getTagCharacters();
		$tag_start = RL_RegEx::quote($tag_start);
		$tag_end   = RL_RegEx::quote($tag_end);

		$pre        = RL_PluginTag::getRegexSurroundingTagsPre();
		$post       = RL_PluginTag::getRegexSurroundingTagsPost();
		$inside_tag = RL_PluginTag::getRegexInsideTag();

		$delimiter = ($params->tag_delimiter == 'space') ? RL_PluginTag::getRegexSpaces() : '=';
		$set_id    = '(?:-[a-zA-Z0-9-_]+)?';

		self::$regexes = (object) [];

		self::$regexes->tag =
			'(?P<pre>' . $pre . ')'
			. $tag_start . '(?P<tag>'
			. $params->tag_open . 's?' . '(?P<set_id>' . $set_id . ')' . $delimiter . '(?P<data>' . $inside_tag . ')'
			. '|/' . $params->tag_close . $set_id
			. ')' . $tag_end
			. '(?P<post>' . $post . ')';

		self::$regexes->end =
			'(?P<pre>' . $pre . ')'
			. $tag_start . '/' . $params->tag_close . $set_id . $tag_end
			. '(?P<post>' . $post . ')';

		self::$regexes->link =
			$tag_start . $params->tag_link . $set_id . $delimiter . '(?P<id>' . $inside_tag . ')' . $tag_end
			. '(?P<text>.*?)'
			. $tag_start . '/' . $params->tag_link . $tag_end;

		return self::$regexes;
	}

	public static function getTagCharacters()
	{
		$params = self::get();

		if ( ! isset($params->tag_character_start))
		{
			self::setTagCharacters();
		}

		return [$params->tag_character_start, $params->tag_character_end];
	}

	public static function setTagCharacters()
	{
		$params = self::get();

		list(self::$params->tag_character_start, self::$params->tag_character_end) = explode('.', $params->tag_characters);
	}
}

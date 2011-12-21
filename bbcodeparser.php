<?php
/**
 * BB Code Parser
 * 
 * Copyright (C) 2011-2012 Ballistiq Digital inc.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

class BBCodeParser
{

	//Web path to smilies
	protected static $smilies_path = "/assets/images/smilies";

	//Custom smilies
	protected static $smilies = array(
		'confused' => 'confused.gif',
		'mad' => 'mad.gif',
		'cool' => 'cool.gif',
		'rolleyes' => 'rolleyes.gif',
		'eek' => 'eek.gif'
	);

	/**
	 * Parse text for bb code
	 */
	public static function parse($text)
	{
		//b
		$text = preg_replace("/\[b\](.*)\[\/b\]/sUi", "<b>$1</b>", $text);

		//i
		$text = preg_replace("/\[i\](.*)\[\/i\]/sUi", "<i>$1</i>", $text);

		//u - underline
		$text = preg_replace("/\[u\](.*)\[\/u\]/sUi", "<u>$1</u>", $text);

		//quote and indent
		$text = preg_replace("/\[quote=(.*);.*\](.*)\[\/quote\]/sUi", "<blockquote><i>$1 said: </i><br />$2</blockquote>", $text);
		$text = preg_replace("/\[quote\](.*)\[\/quote\]/sUi", "<blockquote>$1</blockquote>", $text);
		$text = preg_replace("/\[indent\](.*)\[\/indent\]/sUi", "<blockquote>$1</blockquote>", $text);

		//email
		$text = preg_replace("/\[email=\"(.*)\"\](.*)\[\/email\]/sUi", "<a href=\"mailto:$1\">$2</a>", $text);

		//url
		$text = preg_replace("/\[url=\"(.*)\"\](.*)\[\/url\]/sUi", "<a href=\"$1\">$2</a>", $text);	
		$text = preg_replace("/\[url=(.*)\](.*)\[\/url\]/sUi", "<a href=\"$1\">$2</a>", $text);	
		$text = preg_replace("/\[url\](.*)\[\/url\]/sUi", "<a href=\"$1\">$1</a>", $text);	
		$text = preg_replace("/\[url=\"(.*)\"\].*\[\/url\]/sUi", "<a href=\"$1\">$1</a>", $text);	
		
		//img
		$text = preg_replace("/\[img\](.*)\[\/img\]/sUi", "<img src=\"$1\" />", $text);

		//Code
		$text = preg_replace("/\[code\](.*)\[\/code\]/sUi", "<pre>$1</pre>", $text);

		//Aligns
		$text = preg_replace("/\[left\](.*)\[\/left\]/sUi", "<div style=\"text-align: left;\">$1</div>", $text);
		$text = preg_replace("/\[right\](.*)\[\/right\]/sUi", "<div style=\"text-align: right;\">$1</div>", $text);
		$text = preg_replace("/\[center\](.*)\[\/center\]/sUi", "<div style=\"text-align: center;\">$1</div>", $text);

		//Lists
		$text = preg_replace("/\[list\](.*)\[\/list\]/sUi", "<ul>$1</ul>", $text);	//ul
		$text = preg_replace("/\[list=.*\](.*)\[\/list\]/sUi", "<ol>$1</ol>", $text);	//ol
		$text = preg_replace("/\[\*\](.*)/i", "<li>$1</li>", $text); //li

		//Regular smilies are:  :)  :(  :P  :D  :o  ;)
		$text = preg_replace("/:\)/", "<img src=\"".self::$smilies_path."/smile.gif\" />", $text);
		$text = preg_replace("/:\(/", "<img src=\"".self::$smilies_path."/frown.gif\" />", $text);
		$text = preg_replace("/:P/", "<img src=\"".self::$smilies_path."/tongue.gif\" />", $text);
		$text = preg_replace("/:D/", "<img src=\"".self::$smilies_path."/biggrin.gif\" />", $text);
		$text = preg_replace("/:o/", "<img src=\"".self::$smilies_path."/redface.gif\" />", $text);	//Not sure why this is redface...
		$text = preg_replace("/;\)/", "<img src=\"".self::$smilies_path."/wink.gif\" />", $text);	


		//Custom smilies
		foreach (self::$smilies as $search => $replacement)
		{
			$text = preg_replace("/:$search:/", "<img src=\"".self::$smilies_path."/$replacement\" />", $text);
		}

		//Attachments from VBulletin - for now just cull them
		$text = preg_replace("/\[attach\](.*)\[\/attach\]/sUi", "", $text);
		$text = preg_replace("/\[attach=.*\](.*)\[\/attach\]/sUi", "", $text);

		return $text;
	}


}
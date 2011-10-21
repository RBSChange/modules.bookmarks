<?php
/**
 * bookmarks_BlockBookmarkAllListAction
 * @package modules.bookmarks.lib.blocks
 */
class bookmarks_BlockBookmarkAllListAction extends bookmarks_BlockAbstractBookmarkListAction
{
	/**
	 * @param f_mvc_Request $request
	 * @return f_peristentdocument_PersistentDocument 
	 */
	protected function getParentDoc($request)
	{
		return website_WebsiteService::getInstance()->getCurrentWebsite();
	}
	
	/**
	 * @param f_mvc_Request $request
	 * @return string
	 */
	protected function getBlockTitle($request)
	{
		$title = $this->getConfigurationValue('blockTitle');
		if (!$title)
		{
			$title = LocaleService::getInstance()->transFO('m.bookmarks.fo.bookmarks-from-website-title', array('ucf'));
		}
		return $title;
	}
	
	/**
	 * @param f_mvc_Request $request
	 * @return boolean 
	 */
	protected function isOnDetailPage($request)
	{
		$tag = 'contextual_website_website_modules_bookmarks_bookmarkalllist';
		return TagService::getInstance()->hasTag($this->getContext()->getPersistentPage(), $tag);
	}
}
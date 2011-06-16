<?php
/**
 * bookmarks_BlockBookmarkContextualListAction
 * @package modules.bookmarks.lib.blocks
 */
class bookmarks_BlockBookmarkContextualListAction extends bookmarks_BlockAbstractBookmarkListAction
{
	/**
	 * @param f_mvc_Request $request
	 * @return f_peristentdocument_PersistentDocument 
	 */
	protected function getParentDoc($request)
	{
		return $this->getContext()->getParent();
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
			$doc = $this->getParentDoc($request);
			$title = LocaleService::getInstance()->transFO('m.bookmarks.fo.topic-title', array('ucf', 'html'), array('topic' => $doc->getLabel()));
		}
		return $title;
	}
}
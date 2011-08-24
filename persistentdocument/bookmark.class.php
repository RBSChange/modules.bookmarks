<?php
/**
 * Class where to put your custom methods for document bookmarks_persistentdocument_bookmark
 * @package modules.bookmarks.persistentdocument
 */
class bookmarks_persistentdocument_bookmark extends bookmarks_persistentdocument_bookmarkbase
{
	/**
	 * @return string
	 */
	public function getIndirectUrl()
	{
		return LinkHelper::getActionUrl('bookmarks', 'IndirectUrl', array('cmpref' => $this->getId()));
	}
}
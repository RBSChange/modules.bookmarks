<?php
/**
 * bookmarks_BookmarkScriptDocumentElement
 * @package modules.bookmarks.persistentdocument.import
 */
class bookmarks_BookmarkScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return bookmarks_persistentdocument_bookmark
     */
    protected function initPersistentDocument()
    {
    	return bookmarks_BookmarkService::getInstance()->getNewDocumentInstance();
    }
    
    /**
	 * @return f_persistentdocument_PersistentDocumentModel
	 */
	protected function getDocumentModel()
	{
		return f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName('modules_bookmarks/bookmark');
	}
}
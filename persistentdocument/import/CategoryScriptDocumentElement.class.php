<?php
/**
 * bookmarks_CategoryScriptDocumentElement
 * @package modules.bookmarks.persistentdocument.import
 */
class bookmarks_CategoryScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return bookmarks_persistentdocument_category
     */
    protected function initPersistentDocument()
    {
    	return bookmarks_CategoryService::getInstance()->getNewDocumentInstance();
    }
    
    /**
	 * @return f_persistentdocument_PersistentDocumentModel
	 */
	protected function getDocumentModel()
	{
		return f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName('modules_bookmarks/category');
	}
}
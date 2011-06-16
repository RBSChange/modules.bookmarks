<?php
/**
 * bookmarks_CategoryfolderScriptDocumentElement
 * @package modules.bookmarks.persistentdocument.import
 */
class bookmarks_CategoryfolderScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return bookmarks_persistentdocument_categoryfolder
     */
    protected function initPersistentDocument()
    {
    	return bookmarks_CategoryfolderService::getInstance()->getNewDocumentInstance();
    }
    
    /**
	 * @return f_persistentdocument_PersistentDocumentModel
	 */
	protected function getDocumentModel()
	{
		return f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName('modules_bookmarks/categoryfolder');
	}
}
<?php
/**
 * bookmarks_TreefolderScriptDocumentElement
 * @package modules.bookmarks.persistentdocument.import
 */
class bookmarks_TreefolderScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return bookmarks_persistentdocument_treefolder
     */
    protected function initPersistentDocument()
    {
    	return bookmarks_TreefolderService::getInstance()->getNewDocumentInstance();
    }
    
    /**
	 * @return f_persistentdocument_PersistentDocumentModel
	 */
	protected function getDocumentModel()
	{
		return f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName('modules_bookmarks/treefolder');
	}
}
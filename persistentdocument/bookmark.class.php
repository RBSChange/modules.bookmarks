<?php
/**
 * Class where to put your custom methods for document bookmarks_persistentdocument_bookmark
 * @package modules.bookmarks.persistentdocument
 */
class bookmarks_persistentdocument_bookmark extends bookmarks_persistentdocument_bookmarkbase implements indexer_IndexableDocument
{
	/**
	 * @return indexer_IndexedDocument
	 */
	public function getIndexedDocument()
	{
		$indexedDoc = new indexer_IndexedDocument();
		$indexedDoc->setId($this->getId());
		$indexedDoc->setDocumentModel('modules_bookmarks/bookmark');
		$indexedDoc->setLabel($this->getLabel());
		$indexedDoc->setLang(RequestContext::getInstance()->getLang());
		$indexedDoc->setText(f_util_StringUtils::htmlToText($this->getDescriptionAsHtml()));
		return $indexedDoc;
	}
	
	/**
	 * @return string
	 */
	public function getIndirectUrl()
	{
		return LinkHelper::getActionUrl('bookmarks', 'IndirectUrl', array('cmpref' => $this->getId()));
	}
}
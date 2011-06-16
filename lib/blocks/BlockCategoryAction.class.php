<?php
/**
 * bookmarks_BlockCategoryAction
 * @package modules.bookmarks.lib.blocks
 */
class bookmarks_BlockCategoryAction extends bookmarks_BlockAbstractBookmarkListAction
{
	/**
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 * @return String
	 */
	public function execute($request, $response)
	{
		if ($this->isInBackofficeEdition())
		{
			return website_BlockView::NONE;
		}
		
		$website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
		if ($request->hasParameter('topicId'))
		{
			$topic = website_persistentdocument_topic::getInstanceById($request->getParameter('topicId'));
			$request->setAttribute('topic', $topic);
		}
		
		return parent::execute($request, $response);
	}
	
	/**
	 * @param f_mvc_Request $request
	 * @return f_peristentdocument_PersistentDocument 
	 */
	protected function getParentDoc($request)
	{
		$doc = $this->getDocumentParameter();
		return ($doc instanceof bookmarks_persistentdocument_category) ? $doc : null;
	}
	
	/**
	 * @param f_mvc_Request $request
	 * @return boolean 
	 */
	protected function isOnDetailPage($request)
	{
		$tag = 'contextual_website_website_modules_bookmarks_category';
		return TagService::getInstance()->hasTag($this->getContext()->getPersistentPage(), $tag);
	}

	/**
	 * @param f_mvc_Request $request
	 * @return integer 
	 */
	protected function getDocCount($request)
	{
		$bs = bookmarks_BookmarkService::getInstance();
		$parentDoc = $this->getParentDoc($request);
		$website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
		if ($request->hasAttribute('topic'))
		{
			return $bs->getPublishedCountByCategoryAndTopic($parentDoc, $request->getAttribute('topic'));
		}
		else
		{
			return $bs->getPublishedCountByCategoryAndWebsite($parentDoc, $website);
		}
	}
	
	/**
	 * @param f_mvc_Request $request
	 * @param integer $offset
	 * @param integer $itemsPerPage
	 * @return bookmarks_persistentdocument_bookmark
	 */
	protected function getDocs($request, $offset, $itemsPerPage)
	{
		$bs = bookmarks_BookmarkService::getInstance();
		$parentDoc = $this->getParentDoc($request);
		$website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
		if ($request->hasAttribute('topic'))
		{
			return $bs->getPublishedByCategoryAndTopic($parentDoc, $request->getAttribute('topic'), $offset, $itemsPerPage);
		}
		else
		{
			return $bs->getPublishedByCategoryAndWebsite($parentDoc, $website, $offset, $itemsPerPage);
		}
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
			$title = LocaleService::getInstance()->transFO('m.bookmarks.fo.category-title', array('ucf', 'html'), array('category' => $doc->getLabel()));
		}
		return $title;
	}
}
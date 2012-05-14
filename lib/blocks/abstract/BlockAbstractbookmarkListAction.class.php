<?php
/**
 * bookmarks_BlockAbstractBookmarkListAction
 * @package modules.bookmarks.lib.blocks
 */
abstract class bookmarks_BlockAbstractBookmarkListAction extends website_BlockAction
{
	/**
	 * @return array
	 */
	public function getRequestModuleNames()
	{
		$names = parent::getRequestModuleNames();
		if (!in_array('bookmarks', $names))
		{
			$names[] = 'bookmarks';
		}
		return $names;
	}
	
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
		
		$isOnDetailPage = $this->isOnDetailPage($request);
		$doc = $this->getParentDoc($request);
		if ($doc === null || !$doc->isPublished())
		{
			if ($isOnDetailPage && !$this->isInBackofficePreview())
			{
				HttpController::getInstance()->redirect("website", "Error404");
			}
			return website_BlockView::NONE;
		}
		$request->setAttribute('linkToDetailPage', !$isOnDetailPage && $this->getConfigurationValue('linkToDetailPage', false));
		
		$count = $this->getDocCount($request);
		$request->setAttribute('count', $count);
		if ($count > 0)
		{
			$itemsPerPage = $this->getItemsPerPage($request);
			$page = $request->getParameter('page');
			if (!is_numeric($page) || $page < 1 || $page > ceil($count / $itemsPerPage))
			{
				$page = 1;
			}
			$offset = ($page - 1) * $itemsPerPage;
			$this->getContext()->addCanonicalParam('page', $page > 1 ? $page : null, $this->getModuleName());
	
			$docs = $this->getDocs($request, $offset, $itemsPerPage);
			$paginator = new paginator_Paginator('bookmarks', $page, $docs, $itemsPerPage);
			$paginator->setItemCount($count);
	
			$request->setAttribute('docs', $paginator);
		}
		
		$parentDoc = $this->getParentDoc($request);
		$request->setAttribute('contextdocument', $parentDoc);
		$blockTitle = $this->getBlockTitle($request);
		$request->setAttribute('blockTitle', $blockTitle);
		$request->setAttribute('paginationPosition', $this->getConfigurationValue('paginationPosition'));
		
		return $this->getConfigurationValue('displayMode', website_BlockView::SUCCESS);
	}
	
	/**
	 * @param f_mvc_Request $request
	 * @return f_peristentdocument_PersistentDocument 
	 */
	protected abstract function getParentDoc($request);
	
	/**
	 * @param f_mvc_Request $request
	 * @return boolean 
	 */
	protected function isOnDetailPage($request)
	{
		return false;
	}
	
	/**
	 * @param f_mvc_Request $request
	 * @return boolean 
	 */
	protected function addRssFeeds($request)
	{
		return true;
	}
	
	/**
	 * @param f_mvc_Request $request
	 * @return integer 
	 */
	protected function getDocCount($request)
	{
		$bes = bookmarks_BookmarkService::getInstance();
		$parentDoc = $this->getParentDoc($request);
		$getter = 'getPublishedCountBy'.ucfirst($parentDoc->getPersistentModel()->getDocumentName());
		if (!f_util_ClassUtils::methodExists($bes, $getter))
		{
			throw new Exception('No method ' . $getter . ' in ' . get_class($bes));
		}
		return $bes->{$getter}($parentDoc);
	}
	
	/**
	 * @param f_mvc_Request $request
	 * @param integer $offset
	 * @param integer $itemsPerPage
	 * @return bookmarks_persistentdocument_bookmark
	 */
	protected function getDocs($request, $offset, $itemsPerPage)
	{
		$bes = bookmarks_BookmarkService::getInstance();
		$parentDoc = $this->getParentDoc($request);
		$getter = 'getPublishedBy'.ucfirst($parentDoc->getPersistentModel()->getDocumentName());
		if (!f_util_ClassUtils::methodExists($bes, $getter))
		{
			throw new Exception('No method ' . $getter . ' in ' . get_class($bes));
		}
		return $bes->{$getter}($parentDoc, $offset, $itemsPerPage);
	}
		
	/**
	 * @param f_mvc_Request $request
	 * @return string
	 */
	protected function getBlockTitle($request)
	{
		return $this->getConfigurationValue('blockTitle');
	}

	/**
	 * @param f_mvc_Request $request
	 * @return integer
	 */
	protected function getItemsPerPage($request)
	{
		return intval($this->getConfigurationValue('itemsPerPage', 10));
	}
	
	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	protected function getConfigurationValue($key, $default = null)
	{
		$configuration = $this->getConfiguration();
		$getter = 'get' . ucfirst($key);
		if (f_util_ClassUtils::methodExists($configuration, $getter))
		{
			return f_util_ClassUtils::callMethodOn($configuration, $getter);
		}
		return $default;
	}
	
	/**
	 * @return array<String, String>
	 */
	public function getMetas()
	{
		$doc = $this->getParentDoc($this->getRequest());
		if ($doc !== null && $doc->isPublished())
		{
			$description = (f_util_ClassUtils::methodExists($doc, 'getDescription')) ? $doc->getDescription() : '';
			return array(
				'label' => $doc->getLabel(), 
				'description' => f_util_StringUtils::htmlToText($description)
			);
		}
		return array();
	}
}
<?php
/**
 * bookmarks_BookmarkService
 * @package modules.bookmarks
 */
class bookmarks_BookmarkService extends f_persistentdocument_DocumentService
{
	/**
	 * @var bookmarks_BookmarkService
	 */
	private static $instance;

	/**
	 * @return bookmarks_BookmarkService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}

	/**
	 * @return bookmarks_persistentdocument_bookmark
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_bookmarks/bookmark');
	}

	/**
	 * Create a query based on 'modules_bookmarks/bookmark' model.
	 * Return document that are instance of modules_bookmarks/bookmark,
	 * including potential children.
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_bookmarks/bookmark');
	}
	
	/**
	 * Create a query based on 'modules_bookmarks/bookmark' model.
	 * Only documents that are strictly instance of modules_bookmarks/bookmark
	 * (not children) will be retrieved
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createStrictQuery()
	{
		return $this->pp->createQuery('modules_bookmarks/bookmark', false);
	}

	/**
	 * @param website_persistentdocument_website $website
	 * @return integer
	 */
	public function getPublishedCountAnywhere()
	{
		return $this->findPublishedCount($this->createQuery());
	}
	
	/**
	 * @param website_persistentdocument_website $website
	 * @param integer $offset
	 * @param integer $limit
	 * @return event_persistentdocument_baseevent[]
	 */
	public function getPublishedAnywhere($offset, $limit)
	{
		return $this->findPublished($this->createQuery(), $offset, $limit);
	}
	
	/**
	 * @param website_persistentdocument_website $website
	 * @return integer
	 */
	public function getPublishedCountByWebsite($website)
	{
		$query = $this->createQuery()->add(Restrictions::eq('website', $website));
		return $this->findPublishedCount($query);
	}
	
	/**
	 * @param website_persistentdocument_website $website
	 * @param integer $offset
	 * @param integer $limit
	 * @return event_persistentdocument_baseevent[]
	 */
	public function getPublishedByWebsite($website, $offset, $limit)
	{
		$query = $this->createQuery()->add(Restrictions::eq('website', $website));
		return $this->findPublished($query, $offset, $limit);
	}
	
	/**
	 * @param website_persistentdocument_topic $topic
	 * @return integer
	 */
	public function getPublishedCountByTopic($topic)
	{
		$query = $this->createQuery()->add(Restrictions::eq('topic', $topic));
		return $this->findPublishedCount($query);
	}
	
	/**
	 * @param website_persistentdocument_topic $topic
	 * @param integer $offset
	 * @param integer $limit
	 * @return event_persistentdocument_baseevent[]
	 */
	public function getPublishedByTopic($topic, $offset, $limit)
	{
		$query = $this->createQuery()->add(Restrictions::eq('topic', $topic));
		return $this->findPublished($query, $offset, $limit);
	}
		
	/**
	 * @param event_persistentdocument_category $category
	 * @param website_persistentdocument_website $website
	 * @return integer
	 */
	public function getPublishedCountByCategoryAndWebsite($category, $website)
	{
		$query = $this->createQuery()->add(Restrictions::eq('category', $category))->add(Restrictions::eq('website', $website));
		return $this->findPublishedCount($query);
	}
	
	/**
	 * @param event_persistentdocument_category $category
	 * @param website_persistentdocument_website $website
	 * @param integer $offset
	 * @param integer $limit
	 * @return event_persistentdocument_baseevent[]
	 */
	public function getPublishedByCategoryAndWebsite($category, $website, $offset, $limit)
	{
		$query = $this->createQuery()->add(Restrictions::eq('category', $category))->add(Restrictions::eq('website', $website));
		return $this->findPublished($query, $offset, $limit);
	}
		
	/**
	 * @param event_persistentdocument_category $category
	 * @param website_persistentdocument_website $website
	 * @return integer
	 */
	public function getPublishedCountByCategoryAndTopic($category, $topic)
	{
		$query = $this->createQuery()->add(Restrictions::eq('category', $category))->add(Restrictions::eq('topic', $topic));
		return $this->findPublishedCount($query);
	}
	
	/**
	 * @param event_persistentdocument_category $category
	 * @param website_persistentdocument_website $website
	 * @param integer $offset
	 * @param integer $limit
	 * @return event_persistentdocument_baseevent[]
	 */
	public function getPublishedByCategoryAndTopic($category, $topic, $offset, $limit)
	{
		$query = $this->createQuery()->add(Restrictions::eq('category', $category))->add(Restrictions::eq('topic', $topic));
		return $this->findPublished($query, $offset, $limit);
	}
	
	/**
	 * @param f_persistentdocument_criteria_Query $query
	 * @return integer
	 */
	protected function findPublishedCount($query)
	{
		$query = $this->completeQueryForPublished($query);
		$query->setProjection(Projections::rowCount('count'));
		return f_util_ArrayUtils::firstElement($query->findColumn('count'));
	}
	
	/**
	 * @param f_persistentdocument_criteria_Query $query
	 * @param integer $offset
	 * @param $limit $offset
	 * @return event_persistentdocument_baseevent[]
	 */
	protected function findPublished($query, $offset, $limit)
	{
		$query = $this->completeQueryForPublished($query)->addOrder(Order::asc('label'));
		$query->setFirstResult($offset)->setMaxResults($limit);
		return $query->find();
	}
	
	/**
	 * @param f_persistentdocument_criteria_Query $query
	 * @return f_persistentdocument_criteria_Query
	 */
	protected function completeQueryForPublished($query)
	{
		$query->add(Restrictions::published())->add(Restrictions::isNotEmpty('website'));
		return $query;
	}
	
	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @param Integer $parentNodeId Parent node ID where to save the document (optionnal => can be null !).
	 * @return void
	 */
	protected function preSave($document, $parentNodeId)
	{
		if ($document->isPropertyModified('topic'))
		{
			$this->refreshWebsites($document);
		}
	}
	
	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
//	protected function preInsert($document, $parentNodeId)
//	{
//	}

	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
//	protected function postInsert($document, $parentNodeId)
//	{
//	}

	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
//	protected function preUpdate($document, $parentNodeId)
//	{
//	}

	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
//	protected function postUpdate($document, $parentNodeId)
//	{
//	}

	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
//	protected function postSave($document, $parentNodeId)
//	{
//	}

	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @return void
	 */
//	protected function preDelete($document)
//	{
//	}

	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @return void
	 */
//	protected function preDeleteLocalized($document)
//	{
//	}

	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @return void
	 */
//	protected function postDelete($document)
//	{
//	}

	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @return void
	 */
//	protected function postDeleteLocalized($document)
//	{
//	}

	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @return boolean true if the document is publishable, false if it is not.
	 */
//	public function isPublishable($document)
//	{
//		$result = parent::isPublishable($document);
//		return $result;
//	}


	/**
	 * Methode à surcharger pour effectuer des post traitement apres le changement de status du document
	 * utiliser $document->getPublicationstatus() pour retrouver le nouveau status du document.
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @param String $oldPublicationStatus
	 * @param array<"cause" => String, "modifiedPropertyNames" => array, "oldPropertyValues" => array> $params
	 * @return void
	 */
//	protected function publicationStatusChanged($document, $oldPublicationStatus, $params)
//	{
//	}

	/**
	 * Correction document is available via $args['correction'].
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param Array<String=>mixed> $args
	 */
//	protected function onCorrectionActivated($document, $args)
//	{
//	}

	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @param String $tag
	 * @return void
	 */
//	public function tagAdded($document, $tag)
//	{
//	}

	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @param String $tag
	 * @return void
	 */
//	public function tagRemoved($document, $tag)
//	{
//	}

	/**
	 * @param bookmarks_persistentdocument_bookmark $fromDocument
	 * @param f_persistentdocument_PersistentDocument $toDocument
	 * @param String $tag
	 * @return void
	 */
//	public function tagMovedFrom($fromDocument, $toDocument, $tag)
//	{
//	}

	/**
	 * @param f_persistentdocument_PersistentDocument $fromDocument
	 * @param bookmarks_persistentdocument_bookmark $toDocument
	 * @param String $tag
	 * @return void
	 */
//	public function tagMovedTo($fromDocument, $toDocument, $tag)
//	{
//	}

	/**
	 * Called before the moveToOperation starts. The method is executed INSIDE a
	 * transaction.
	 *
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param Integer $destId
	 */
//	protected function onMoveToStart($document, $destId)
//	{
//	}

	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @param Integer $destId
	 * @return void
	 */
//	protected function onDocumentMoved($document, $destId)
//	{
//	}

	/**
	 * this method is call before saving the duplicate document.
	 * If this method not override in the document service, the document isn't duplicable.
	 * An IllegalOperationException is so launched.
	 *
	 * @param bookmarks_persistentdocument_bookmark $newDocument
	 * @param bookmarks_persistentdocument_bookmark $originalDocument
	 * @param Integer $parentNodeId
	 *
	 * @throws IllegalOperationException
	 */
//	protected function preDuplicate($newDocument, $originalDocument, $parentNodeId)
//	{
//		throw new IllegalOperationException('This document cannot be duplicated.');
//	}

	/**
	 * this method is call after saving the duplicate document.
	 * $newDocument has an id affected.
	 * Traitment of the children of $originalDocument.
	 *
	 * @param bookmarks_persistentdocument_bookmark $newDocument
	 * @param bookmarks_persistentdocument_bookmark $originalDocument
	 * @param Integer $parentNodeId
	 *
	 * @throws IllegalOperationException
	 */
//	protected function postDuplicate($newDocument, $originalDocument, $parentNodeId)
//	{
//	}
	
	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 */
	public function refreshWebsites($document)
	{
		$websiteIds = array();
		foreach ($document->getTopicArray() as $topic)
		{
			$websiteIds[] = $topic->getDocumentService()->getWebsiteId($topic);
		}
		$websites = website_WebsiteService::getInstance()->createQuery()->add(Restrictions::in('id', $websiteIds))->find();
		$document->setWebsiteArray($websites);
	}
	
	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @return integer | null
	 */
	public function getWebsiteId($document)
	{
		$website = $document->getWebsite(0);
		return ($website !== null) ? $website->getId() : null;
	}
	
	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @return integer[] | null
	 */
	public function getWebsiteIds($document)
	{
		$websites = $document->getWebsiteArray();
		return DocumentHelper::getIdArrayFromDocumentArray($websites);
	}

	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @param website_persistentdocument_website $website
	 */
	public function getPrimaryTopicForWebsite($document, $website)
	{
		$query = website_TopicService::getInstance()->createQuery()->add(Restrictions::descendentOf($website->getId()));
		$query->add(Restrictions::published())->add(Restrictions::eq('bookmark', $document))->setMaxResults(1);
		return f_util_ArrayUtils::firstElement($query->find());
	}

	/**
	 * @param website_UrlRewritingService $urlRewritingService
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @param array $parameters
	 * @return f_web_Link | null
	 */
	public function getWebLink($urlRewritingService, $document, $website, $lang, $parameters)
	{
		if ($document->getHasDetailPage())
		{
			return parent::getWebLink($urlRewritingService, $document, $website, $lang, $parameters);
		}
		else
		{
			$parameters['cmpref'] = $document->getId();
			$parameters['lang'] = $lang;
			return $urlRewritingService->getActionLinkForWebsite('bookmarks', 'IndirectUrl', $website, $lang, $parameters);
		}
	}
	
	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @return website_persistentdocument_page | null
	 */
	public function getDisplayPage($document)
	{
		$request = HttpController::getInstance()->getContext()->getRequest();
		if ($request->hasModuleParameter('bookmark', 'topicId'))
		{
			$topicId = $request->getModuleParameter('bookmark', 'topicId');
		}
		else
		{
			$topic = $this->getPrimaryTopicForWebsite($document, website_WebsiteModuleService::getInstance()->getCurrentWebsite());
			$topicId = $topic ? $topic->getId() : null;
		}
		
		if ($topicId > 0)
		{
			return website_PageService::getInstance()->createQuery()
				->add(Restrictions::published())
				->add(Restrictions::childOf($topicId))
				->add(Restrictions::hasTag('functional_bookmarks_bookmark-detail'))
				->findUnique();
		}
		return null;
	}

	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @param string $forModuleName
	 * @param array $allowedSections
	 * @return array
	 */
	public function getResume($document, $forModuleName, $allowedSections = null)
	{
		$resume = parent::getResume($document, $forModuleName, $allowedSections);
		$resume['properties']['url'] = $document->getUrl();
		return $resume;
	}

	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @param string $bockName
	 * @return array with entries 'module' and 'template'. 
	 */
//	public function getSolrserachResultItemTemplate($document, $bockName)
//	{
//		return array('module' => 'bookmarks', 'template' => 'Bookmarks-Inc-BookmarkResultDetail');
//	}

	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @param string $moduleName
	 * @param string $treeType
	 * @param array<string, string> $nodeAttributes
	 */
//	public function addTreeAttributes($document, $moduleName, $treeType, &$nodeAttributes)
//	{
//	}
	
	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @param String[] $propertiesName
	 * @param Array $datas
	 */
//	public function addFormProperties($document, $propertiesName, &$datas)
//	{
//	}
}
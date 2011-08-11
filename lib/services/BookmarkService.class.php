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
	 * @return bookmarks_persistentdocument_bookmark[]
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
	 * @return bookmarks_persistentdocument_bookmark[]
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
	 * @return bookmarks_persistentdocument_bookmark[]
	 */
	public function getPublishedByTopic($topic, $offset, $limit)
	{
		$query = $this->createQuery()->add(Restrictions::eq('topic', $topic));
		return $this->findPublished($query, $offset, $limit);
	}
		
	/**
	 * @param bookmarks_persistentdocument_category $category
	 * @param website_persistentdocument_website $website
	 * @return integer
	 */
	public function getPublishedCountByCategoryAndWebsite($category, $website)
	{
		$query = $this->createQuery()->add(Restrictions::eq('category', $category))->add(Restrictions::eq('website', $website));
		return $this->findPublishedCount($query);
	}
	
	/**
	 * @param bookmarks_persistentdocument_category $category
	 * @param website_persistentdocument_website $website
	 * @param integer $offset
	 * @param integer $limit
	 * @return bookmarks_persistentdocument_bookmark[]
	 */
	public function getPublishedByCategoryAndWebsite($category, $website, $offset, $limit)
	{
		$query = $this->createQuery()->add(Restrictions::eq('category', $category))->add(Restrictions::eq('website', $website));
		return $this->findPublished($query, $offset, $limit);
	}
		
	/**
	 * @param bookmarks_persistentdocument_category $category
	 * @param website_persistentdocument_website $website
	 * @return integer
	 */
	public function getPublishedCountByCategoryAndTopic($category, $topic)
	{
		$query = $this->createQuery()->add(Restrictions::eq('category', $category))->add(Restrictions::eq('topic', $topic));
		return $this->findPublishedCount($query);
	}
	
	/**
	 * @param bookmarks_persistentdocument_category $category
	 * @param website_persistentdocument_website $website
	 * @param integer $offset
	 * @param integer $limit
	 * @return bookmarks_persistentdocument_bookmark[]
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
	 * @return bookmarks_persistentdocument_bookmark[]
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
		$topics = $document->getPublishedTopicArray();
		$topicIds = DocumentHelper::getIdArrayFromDocumentArray($topics);
				
		$query = website_TopicService::getInstance()->createQuery()->add(Restrictions::descendentOf($website->getId()));
		$query->add(Restrictions::published())->add(Restrictions::in('id', $topicIds))->setProjection(Projections::property('id'));
		$ids = $query->findColumn('id');
		
		foreach ($topics as $topic)
		{
			if (in_array($topic->getId(), $ids))
			{
				return $topic;
			}
		}
		return null;
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
		$request = change_Controller::getInstance()->getContext()->getRequest();
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
	 * @param integer $websiteId
	 * @return array
	 */
	public function getReplacementsForTweet($document, $websiteId)
	{
		$label = array(
			'name' => 'label',
			'label' => LocaleService::getInstance()->transBO('m.bookmarks.document.bookmark.label', array('ucf')),
			'maxLength' => 80
		);
		$shortUrl = array(
			'name' => 'shortUrl', 
			'label' => LocaleService::getInstance()->transBO('m.twitterconnect.bo.general.short-url', array('ucf')),
			'maxLength' => 30
		);
		if ($document !== null)
		{
			$website = website_persistentdocument_website::getInstanceById($websiteId);
			$label['value'] = f_util_StringUtils::shortenString($document->getLabel(), 80);
			$shortUrl['value'] = website_ShortenUrlService::getInstance()->shortenUrl(LinkHelper::getDocumentUrlForWebsite($document, $website));
		}
		return array($label, $shortUrl);
	}

	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @return f_persistentdocument_PersistentDocument[]
	 */
	public function getContainersForTweets($document)
	{
		$containers = $document->getCategoryArray();
		$containers[] = $this->getParentOf($document);
		return $containers;
	}
	
	/**
	 * @param bookmarks_persistentdocument_bookmark $document
	 * @return website_persistentdocument_website[]
	 */
	public function getWebsitesForTweets($document)
	{
		return $document->getPublishedWebsiteArray();
	}
}
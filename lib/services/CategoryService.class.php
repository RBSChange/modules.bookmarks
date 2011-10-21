<?php
/**
 * bookmarks_CategoryService
 * @package modules.bookmarks
 */
class bookmarks_CategoryService extends f_persistentdocument_DocumentService
{
	/**
	 * @var bookmarks_CategoryService
	 */
	private static $instance;

	/**
	 * @return bookmarks_CategoryService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * @return bookmarks_persistentdocument_category
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_bookmarks/category');
	}

	/**
	 * Create a query based on 'modules_bookmarks/category' model.
	 * Return document that are instance of modules_bookmarks/category,
	 * including potential children.
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_bookmarks/category');
	}
	
	/**
	 * Create a query based on 'modules_bookmarks/category' model.
	 * Only documents that are strictly instance of modules_bookmarks/category
	 * (not children) will be retrieved
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createStrictQuery()
	{
		return $this->pp->createQuery('modules_bookmarks/category', false);
	}
	
	/**
	 * @param website_persistentdocument_topic $topic
	 * @return array each element is associative array with the category as 'category' and the bookmark count as 'count'  
	 */
	public function getPublishedInfosByTopic($topic)
	{
		return $this->doGetPublishedInfos($topic);
	}
	
	/**
	 * @param website_persistentdocument_website $website
	 * @return array each element is associative array with the category as 'category' and the bookmark count as 'count'
	 */
	public function getPublishedInfosByWebsite($website)
	{
		return $this->doGetPublishedInfos($website);
	}
	
	/**
	 * @return array each element is associative array with the category as 'category' and the bookmark count as 'count'
	 */
	public function getPublishedInfos()
	{
		return $this->doGetPublishedInfos(null);
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocument $context
	 * @return array each element is associative array with the category as 'category' and the bookmark count as 'count'
	 */
	protected function doGetPublishedInfos($context)
	{
		$query = $this->createQuery()->add(Restrictions::published());
		$criteria = $query->createCriteria('bookmark')->add(Restrictions::published());
		$criteria->setProjection(Projections::rowCount('count'));
		$query->setProjection(Projections::this())->addOrder(Order::iasc('label'));
		
		if ($context instanceof website_persistentdocument_topic)
		{
			$criteria->add(Restrictions::eq('topic', $context));
		}
		else if ($context instanceof website_persistentdocument_website)
		{
			$criteria->add(Restrictions::eq('website', $context));
		}
		else if ($context !== null)
		{
			Framework::warn(__METHOD__ . ' Context ignored: unexpected document type (' . get_class($context) . ')');
		}
		
		$rows = $query->find();
		$result = array();
		foreach ($rows as $row)
		{
			$result[] = array('category' => $row['this'], 'count' => $row['count']);
		}
		return $result;
	}
	
	/**
	 * @param bookmarks_persistentdocument_category $document
	 * @return website_persistentdocument_page | null
	 */
	public function getDisplayPage($document)
	{
		$tag = 'contextual_website_website_modules_bookmarks_category';
		$website = website_WebsiteService::getInstance()->getCurrentWebsite();
		return TagService::getInstance()->getDocumentByContextualTag($tag, $website);
	}
	
	/**
	 * @param bookmarks_persistentdocument_category $document
	 * @return string[]
	 */
	public function getDocumentsModelNamesForTweet($document)
	{
		return array('modules_bookmarks/bookmark');
	}
	
	/**
	 * @param bookmarks_persistentdocument_category $document
	 * @return boolean
	 */
	public function canSendTweetOnContainedDocumentPublish($document)
	{
		return true;
	}
	
	/**
	 * @param bookmarks_persistentdocument_category $document
	 * @param string $modelName
	 * @return integer[]
	 */
	public function getContainedIdsForTweet($document, $modelName)
	{
		$query = bookmarks_BookmarkService::getInstance()->createQuery()->add(Restrictions::published());
		$query->add(Restrictions::eq('category', $document))->setProjection(Projections::property('id'));
		return $query->findColumn('id');
	}
	
	/**
	 * @param bookmarks_persistentdocument_category $document
	 * @return website_persistentdocument_website[]
	 */
	public function getWebsitesForTweets($document)
	{
		return website_WebsiteService::getInstance()->createQuery()->add(Restrictions::published())->find();
	}
}
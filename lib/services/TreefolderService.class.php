<?php
/**
 * bookmarks_TreefolderService
 * @package modules.bookmarks
 */
class bookmarks_TreefolderService extends generic_FolderService
{
	/**
	 * @var bookmarks_TreefolderService
	 */
	private static $instance;

	/**
	 * @return bookmarks_TreefolderService
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
	 * @return bookmarks_persistentdocument_treefolder
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_bookmarks/treefolder');
	}

	/**
	 * Create a query based on 'modules_bookmarks/treefolder' model.
	 * Return document that are instance of modules_bookmarks/treefolder,
	 * including potential children.
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_bookmarks/treefolder');
	}
	
	/**
	 * Create a query based on 'modules_bookmarks/treefolder' model.
	 * Only documents that are strictly instance of modules_bookmarks/treefolder
	 * (not children) will be retrieved
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createStrictQuery()
	{
		return $this->pp->createQuery('modules_bookmarks/treefolder', false);
	}
}
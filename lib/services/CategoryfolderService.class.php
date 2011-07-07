<?php
/**
 * bookmarks_CategoryfolderService
 * @package modules.bookmarks
 */
class bookmarks_CategoryfolderService extends generic_FolderService
{
	/**
	 * @var bookmarks_CategoryfolderService
	 */
	private static $instance;

	/**
	 * @return bookmarks_CategoryfolderService
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
	 * @return bookmarks_persistentdocument_categoryfolder
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_bookmarks/categoryfolder');
	}

	/**
	 * Create a query based on 'modules_bookmarks/categoryfolder' model.
	 * Return document that are instance of modules_bookmarks/categoryfolder,
	 * including potential children.
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_bookmarks/categoryfolder');
	}
	
	/**
	 * Create a query based on 'modules_bookmarks/categoryfolder' model.
	 * Only documents that are strictly instance of modules_bookmarks/categoryfolder
	 * (not children) will be retrieved
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createStrictQuery()
	{
		return $this->pp->createQuery('modules_bookmarks/categoryfolder', false);
	}
}
<?php
/**
 * bookmarks_BlockCategoryAllListAction
 * @package modules.bookmarks.lib.blocks
 */
class bookmarks_BlockCategoryAllListAction extends website_BlockAction
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
		
		$cs = bookmarks_CategoryService::getInstance();
		$website = website_WebsiteService::getInstance()->getCurrentWebsite();
		$request->setAttribute('website', $website);
		$categoriesInfos = $cs->getPublishedInfosByWebsite($website);
		if (count($categoriesInfos) > 0)
		{
			$request->setAttribute('categoriesInfos', $categoriesInfos);
		}
		
		$request->setAttribute('blockTitle', $this->getConfiguration()->getBlockTitle());

		return website_BlockView::SUCCESS;
	}
}
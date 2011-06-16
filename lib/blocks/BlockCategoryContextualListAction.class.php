<?php
/**
 * bookmarks_BlockCategoryContextualListAction
 * @package modules.bookmarks.lib.blocks
 */
class bookmarks_BlockCategoryContextualListAction extends website_BlockAction
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
		
		$topic = $this->getContext()->getParent();
		$request->setAttribute('topic', $topic);
		$categoriesInfos = bookmarks_CategoryService::getInstance()->getPublishedInfosByTopic($topic);
		if (count($categoriesInfos) > 0)
		{
			$request->setAttribute('categoriesInfos', $categoriesInfos);
		}
		
		$request->setAttribute('blockTitle', $this->getConfiguration()->getBlockTitle());

		return website_BlockView::SUCCESS;
	}
}
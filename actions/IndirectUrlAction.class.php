<?php
/**
 * bookmarks_IndirectUrlAction
 * @package modules.bookmarks.actions
 */
class bookmarks_IndirectUrlAction extends f_action_BaseAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$bookmark = bookmarks_persistentdocument_bookmark::getInstanceById($request->getParameter('cmpref'));
		$url = $bookmark->getUrl();
		if ($url !== null)
		{
			HttpController::getInstance()->redirectToUrl($url);
		}
		else
		{
			HttpController::getInstance()->redirect('website', 'Error404');
		}
	}
	
	/**
	 * @return Boolean
	 */
	public function isSecure()
	{
		return false;
	}
}
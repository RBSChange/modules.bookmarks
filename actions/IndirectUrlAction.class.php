<?php
/**
 * bookmarks_IndirectUrlAction
 * @package modules.bookmarks.actions
 */
class bookmarks_IndirectUrlAction extends change_Action
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$bookmark = bookmarks_persistentdocument_bookmark::getInstanceById($request->getParameter('cmpref'));
		$url = $bookmark->getUrl();
		if ($url !== null)
		{
			change_Controller::getInstance()->redirectToUrl($url);
		}
		else
		{
			change_Controller::getInstance()->redirect('website', 'Error404');
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
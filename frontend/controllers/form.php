<?php
/*------------------------------------------------------------------------
# com_ask - Ask (Questions)
# ------------------------------------------------------------------------
# @author    Alexandros D
# @copyright Copyright (C) 2011 Alexandros D. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @Website: http://alexd.mplofa.com
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
 jimport('joomla.application.component.controllerform');

class AskControllerForm extends JControllerForm {
	
	public function save(){ //Just For Logging Reference
		global $logger;
		$logger->info("AskControllerForm::save()");
		
		//Transparent Captcha
		$captcha = JRequest::getString("LastName");
		if ($captcha){
			JError::raiseError(404 , ""); 
		}
		
		
		$data = JRequest::getVar('jform', array(), 'post', 'array');	
		
		//Encode the tags to json..
		$tags = $data["tags"];
		if ($tags){
			$tags = explode("," , $tags);
			$tags = json_encode ( $tags );
			$tags = str_replace(' ', '', $tags);
			$tags = str_replace(',""', '', $tags);
			$tags = json_decode($tags);
			//remove duplicates
			$tags = array_values(array_unique($tags));
			$tags = json_encode ( $tags );
			$data['tags'] = $tags;
			//replace the original request
			JRequest::setVar("jform" , $data);
		}		
		
		if (parent::save()){
			$db = &JFactory::getDbo();
			//redirect & display the inserted data
			$this->setRedirect(JRoute::_("index.php?option=com_ask&view=question&id=") . $db->insertid());
			//clear state
			JFactory::getApplication()->setUserState("com_ask.edit.question.data", array());
		}
		else {
			//store temp data
			JFactory::getApplication()->setUserState("com_ask.edit.question.data", $data);
		}
	}
	
	public function cancel(){
		echo "<script type='text/javascript'>javascript:history.go(-2);</script>";
		//clear state
		JFactory::getApplication()->setUserState("com_ask.edit.question.data", array());
	}
	
}
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
 
// import Joomla view library
jimport('joomla.application.component.view');

class AskViewQuestions extends JView
{
        // Overwriting JView display method
        function display($tpl = null) 
        {
        	global $logger;
        	
        	$app = JFactory::getApplication();
        	$pathway = $app->getPathway();
        	$user = JFactory::getUser();
        	$this->document = JFactory::getDocument();
        	
        	$this->questions = $this->get("Items");
        	$this->pagination = $this->get("Pagination");
        	
        	$this->filteringOptions = $this->get("filteringOptions");
        	$this->sortingOptions = $this->get("sortingOptions");
        	$this->activeFilter = JRequest::getString("filter");
        	
        	//Category View
        	$this->categoryView = FALSE; //Initialization
        	if (JRequest::getInt( "catid" , 0 ))
        		$this->categoryView = TRUE;
        		
        	//Tag View
        	$this->tag = JRequest::getString("tag" , NULL);
        	
        	//Authorizations
        	$this->assignRef("viewanswers", $user->authorize("question.viewanswers" , "com_ask"));
        	$this->assignRef("submitanswers", $user->authorize("question.answer" , "com_ask"));
        	
        	//params
        	$params = $app->getParams();
        	$this->assignRef("params", $params);
        	$this->assignRef("pageclass_sfx" , htmlspecialchars($params->get('pageclass_sfx')));
        	
        	//view options
        	$appParams = json_decode(JFactory::getApplication()->getParams());
        	$this->viewStats = $appParams->display_stats;
        	$this->viewFilteringOptions = $appParams->display_filters;
        	$this->viewGravatars = $appParams->display_gravatars;
        	
        	
        	//Add Pathway
        	AskHelper::addPathway();    	
        	       
        	if ( @$this->questions ){ //check for questions, suppressing errors..
	        	
	        	// Add feed links
				$link = '&format=feed&limitstart=';
				$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
				$this->document->addHeadLink(JRoute::_($link . '&type=rss'), 'alternate', 'rel', $attribs);
				$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
				$this->document->addHeadLink(JRoute::_($link . '&type=atom'), 'alternate', 'rel', $attribs);
        		
	        	parent::display($tpl);
        	}
        	else{
        		$logger->error("No Results..");
        		JError::raiseNotice(404, JText::_("ERROR_404"));
        	}
        	
        }
}

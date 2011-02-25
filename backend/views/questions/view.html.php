<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');


class AskViewQuestions extends JView
{
        function display($tpl = null) 
        {        	
        	$this->items = $this->get('Items');
			$this->pagination = $this->get('Pagination');
			$this->state = $this->get("State");
			
			$this->addToolBar();
			
			//Calculate parents..
			foreach ($this->items as $item){
				$parent = NULL;
				if ($item->parent){
					$q = "SELECT * FROM #__ask WHERE id=" . (int)$item->parent;
					$db = JFactory::getDBO();
					$db->setQuery($q);
					$parent = $db->loadObject();
				}
				$item->parentData = $parent;
			}
			
            // Display the template
            parent::display($tpl);
        }
        
		protected function addToolBar() 
        {
        	$user= JFactory::getUser();
        	
            JToolBarHelper::title(JText::_('Questions'));
            
            AskHelper::canDo("question.edit") ? JToolBarHelper::addNewX('question.add') : NULL ;
            AskHelper::canDo("question.edit") ? JToolBarHelper::editListX('question.edit') : NULL;
            AskHelper::canDo("question.publish") ? JToolBarHelper::publishList("questions.publish") : NULL;
            AskHelper::canDo("question.publish") ? JToolBarHelper::unpublishList("questions.unpublish") : NULL;
            AskHelper::canDo("question.delete") ? JToolBarHelper::deleteListX('Delete?', 'questions.delete') : NULL;
            AskHelper::canDo("question.edit") ? JToolBarHelper::preferences("com_ask") : NULL;
        }
}

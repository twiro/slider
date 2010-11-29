<?php

	Class fieldSlider extends Field {
		public function __construct(&$parent){
			parent::__construct($parent);
			$this->_name = __('Slider');
		}
		

		// Also store 'from' and 'to', for filtering purposes:
		public function processRawFieldData($data, &$status, $simulate = false, $entry_id = null) {
			$status = self::__OK__;
			
			if (strlen(trim($data)) == 0) return array();
			
			$values = explode('-', $data);
			
			$result = array(
				'value' => $data
			);
			
			if(count($values) == 2)
			{
				$result['value_from'] = $values[0];
				$result['value_to'] = $values[1];
			}
			
			return $result;
		}
		

		// Filter functions:
		/*
		public function canFilter()
		{
			return true;
		}
		*/
		
		
		// Publish panel (on publish page):
		public function displayPublishPanel(&$wrapper, $data=NULL, $flagWithError=NULL, $fieldnamePrefix=NULL, $fieldnamePostfix=NULL){
			// Add stylesheet to head:
			if ($this->_engine->Page) {
				$this->_engine->Page->addStylesheetToHead(URL . '/extensions/slider/assets/smoothness/jquery-ui-1.8.6.custom.css', 'screen', 75);
				$this->_engine->Page->addStylesheetToHead(URL . '/extensions/slider/assets/slider.css', 'screen', 76);
				$this->_engine->Page->addScriptToHead(URL . '/extensions/slider/assets/jquery-ui-1.8.6.custom.min.js', 77);
				$this->_engine->Page->addScriptToHead(URL . '/extensions/slider/assets/slider.js', 78);
			}
			$value = General::sanitize($data['value']);
			if(empty($value))
			{
				$value = $this->get('start_value');
			}
			$label = Widget::Label($this->get('label'));
			if($this->get('required') != 'yes') $label->appendChild(new XMLElement('i', __('Optional')));
			$label->appendChild(Widget::Input('fields'.$fieldnamePrefix.'['.$this->get('element_name').']'.$fieldnamePostfix, (strlen($value) != 0 ? $value : NULL), 'text', array('readonly'=>'readonly')));
			$label->appendChild(new XMLElement('div', '', array('class'=>'slider')));
			
			// Add variables:
			$label->appendChild(new XMLElement('var', $this->get('min_range'), array('class'=>'min_range')));
			$label->appendChild(new XMLElement('var', $this->get('max_range'), array('class'=>'max_range')));
			$label->appendChild(new XMLElement('var', $this->get('range'), array('class'=>'range')));
			$label->appendChild(new XMLElement('var', $this->get('increment_value'), array('class'=>'increment_value')));
			
			// In case of an error:
			if($flagWithError != NULL) $wrapper->appendChild(Widget::wrapFormElementWithError($label, $flagWithError));
			else $wrapper->appendChild($label);
		}
		
		
		// Store the section
		public function commit(){
			if(!parent::commit()) return false;
			
			$id = $this->get('id');
			if($id === false) return false;
			
			$fields = array();
			$fields['field_id'] = $id;
			$fields['range'] = $this->get('range') == false ? 0 : 1;
			$fields['min_range'] = $this->get('min_value');
			$fields['max_range'] = $this->get('max_value');
			$fields['start_value'] = $this->get('start_value');
			$fields['increment_value'] = $this->get('increment_value');
			
			Symphony::Database()->query("DELETE FROM `tbl_fields_".$this->handle()."` WHERE `field_id` = '$id' LIMIT 1");
			
			return Symphony::Database()->insert($fields, 'tbl_fields_' . $this->handle());			
		}
		
		
		// Show the settings panel on the sections-page
		public function displaySettingsPanel(&$wrapper, $errors = null)
		{
			parent::displaySettingsPanel($wrapper, $errors);
			
			$div = new XMLElement('div', NULL, array('class' => 'group'));
			$label = Widget::Label(__('Minimum value'));
			$label->appendChild(Widget::Input('fields['.$this->get('sortorder').'][min_value]', $this->get('min_range')));
			$div->appendChild($label);
			
			$label = Widget::Label(__('Maximum value'));
			$label->appendChild(Widget::Input('fields['.$this->get('sortorder').'][max_value]', $this->get('max_range')));
			$div->appendChild($label);
			
			$wrapper->appendChild($div);
			
			$div = new XMLElement('div', NULL, array('class' => 'group'));
			$label = Widget::Label(__('Start Value'));
			$label->appendChild(Widget::Input('fields['.$this->get('sortorder').'][start_value]', $this->get('start_value')));
			$div->appendChild($label);
			
			$label = Widget::Label(__('Incremental value'));
			$label->appendChild(Widget::Input('fields['.$this->get('sortorder').'][increment_value]', $this->get('increment_value')));
			$div->appendChild($label);
			
			$wrapper->appendChild($div);
			
			$label = Widget::Label();
			$attributes = array('type'=>'checkbox', 'name'=>'fields['.$this->get('sortorder').'][range]', 'value'=>'yes');			
			if($this->get('range') == 1)
			{
				$attributes['checked'] = 'checked';
			}
			$checkbox = new XMLElement('input', ' Define a range', $attributes);
			$label->appendChild($checkbox);
			$wrapper->appendChild($label);
			$this->appendShowColumnCheckbox($wrapper);
		}
		
		
		public function createTable(){
			return Symphony::Database()->query(
				"CREATE TABLE IF NOT EXISTS `tbl_entries_data_" . $this->get('id') . "` (
				  `id` int(11) unsigned NOT NULL auto_increment,
				  `entry_id` int(11) unsigned NOT NULL,
				  `value` varchar(255) default NULL,
				  `value_from` varchar(255) default NULL,
				  `value_to` varchar(255) default NULL,
				  PRIMARY KEY  (`id`),
				  KEY `entry_id` (`entry_id`),
				  KEY `value` (`value`)
				) ENGINE=MyISAM;"
			);
		}
		
	}


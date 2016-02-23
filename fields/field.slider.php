<?php

	Class fieldSlider extends Field {
      
		/**
		 *
		 * Constructor for the Field object
		 */
		 
		public function __construct() {
			// call the parent constructor
			parent::__construct();
			// set the name of the field
			$this->_name = __('Slider');
		}
		
		
		public function canFilter(){
			return true;
		}
		
		
		/**
		 *
		 * Save field settings into the field's table
		 */
		 
		 public function commit() {
		 	
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
		
		
		/**
		 *
		 * Process data before saving into database.
		 *
		 * @param array $data
		 * @param int $status
		 * @param $message
		 * @param boolean $simulate
		 * @param int $entry_id
		 *
		 * @return Array - data to be inserted into DB
		 */
		 
		public function processRawFieldData($data, &$status, &$message=null, $simulate = false, $entry_id = null) {
			
			$status = self::__OK__;
			
			if (strlen(trim($data)) == 0) return array();
			
			$values = explode('-', $data);
			
			$result = array(
				'value' => $data
			);
			
			if(count($values) == 2) {
				$result['value_from'] = $values[0];
				$result['value_to'] = $values[1];
			}
			
			return $result;
		}


		/* ******* DATA SOURCE ******* */
		

		/**
		 * Build SQL for fetching the data from the DB
		 *
		 * @param $data
		 * @param $joins
		 * @param $where
		 * @param $andOperation
		 */
		
		public function buildDSRetrievalSQL($data, &$joins, &$where, $andOperation = false) {

			$field_id = $this->get('id');
			
			if (!is_array($data)) $data = array($data);

			$i = 0;

			foreach($data as $filterValue) {

				$this->_key++;

				$joins .= "LEFT JOIN `tbl_entries_data_{$field_id}` AS t{$field_id}_{$this->_key} ON (e.id = t{$field_id}_{$this->_key}.entry_id)";

				$andOr = $andOperation ? ' AND ' : ' OR ';
				if($i == 0) { $andOr = 'AND'; }
				$i++;
				
				// FILTER VARIABLES
				$filterValue = $this->cleanValue($filterValue);
				$filterRange = $this->get('range');
				$filterMode;
				$value;
				$value_to;
				$value_from;
				
				// CHECK FILTER MODE : BETWEEN
				if(preg_match('/^(\d*)(\sto\s|-)(\d*)/', $filterValue)) {
					
					// Will test whether the 'to' range operator or n-n format has been used in the datasource filter or parameter
					if(preg_match('/^(\d*)\sto\s(\d*)/', $filterValue)) {
						$data = explode('to', $filterValue);
					} elseif(preg_match('/^(\d*)-(\d*)/', $filterValue)) {
						$data = explode('-', $filterValue);
					}
					
					$filterMode = 'between';
					$value_from = trim($data[0]); // Start value of filtering range
					$value_to = trim($data[1]); // End value of filtering range
					$value = $value_from.'-'.$value_to; // Range value to match database
					
				// CHECK FILTER MODE : GREATER THAN, LESS THAN, SMALLER THAN
				} elseif(preg_match('/^(greater\sthan|less\sthan|smaller\sthan)(\d*)/', $filterValue)) {
					
					if(preg_match('/^greater\sthan\s(\d*)/', $filterValue)){
						$data = explode('greater than', $filterValue);
						$filterMode = 'greater than';
					} elseif(preg_match('/^less\sthan\s(\d*)/', $filterValue)){
						$data = explode('less than', $filterValue);
						$filterMode = 'less than';
					} elseif(preg_match('/^smaller\sthan\s(\d*)/', $filterValue)){
						$data = explode('smaller than', $filterValue);
						$filterMode = 'less than';
					}
					
					$value = trim($data[1]);
					
				// CHECK FILTER MODE : GREATER THAN, LESS THAN, SMALLER THAN
				} elseif(preg_match('/^([<>])(\d*)/', $filterValue)) {
					
					if(preg_match('/^>\s(\d*)/', $filterValue)){
						$data = explode('>', $filterValue);
						$filterMode = 'greater than';
					} elseif(preg_match('/^<\s(\d*)/', $filterValue)){
						$data = explode('<', $filterValue);
						$filterMode = 'less than';
					}
					
					$value = trim($data[1]);
					
				// CHECK FILTER MODE : IS
				} else {
					
					$filterMode = 'is';
					$value = trim($filterValue);
					
				}
				
				
				// BUILD SQL : FILTER MODE "IS"
				if($filterMode == 'is') {
					if(!$filterRange) {
						$where .= " {$andOr} (
							( t{$field_id}_{$this->_key}.value + 0 ) = '{$value}'
						)";
					} else {
						$where .= " {$andOr} (
							( t{$field_id}_{$this->_key}.value_from + 0 ) <= '{$value}'
							AND
							( t{$field_id}_{$this->_key}.value_to + 0 ) >= '{$value}'
						)";
					}
				
				// BUILD SQL : FILTER MODE "LESS THAN"
				} elseif($filterMode == 'less than') {
					if(!$filterRange) {
						$where .= " {$andOr} (
							( t{$field_id}_{$this->_key}.value + 0 ) < '{$value}'
						)";
					} else {
						$where .= " {$andOr} (
							( t{$field_id}_{$this->_key}.value_to + 0 ) < '{$value}'
						)";
					}
					
				// BUILD SQL : FILTER MODE "GREATER THAN"
				} elseif($filterMode == 'greater than') {
					if(!$filterRange) {
						$where .= " {$andOr} (
							( t{$field_id}_{$this->_key}.value + 0 ) > '{$value}'
						)";
					} else {
						$where .= " {$andOr} (
							( t{$field_id}_{$this->_key}.value_from + 0 ) > '{$value}'
						)";
					}
				
				// BUILD SQL : FILTER MODE "BETWEEN"
				} elseif($filterMode == 'between') {
					if(!$filterRange) {
						$where .= " {$andOr} (
							( t{$field_id}_{$this->_key}.value + 0 ) >= '{$value_from}'
							AND
							( t{$field_id}_{$this->_key}.value + 0 ) <= '{$value_to}'
						)";
					} else {
						$where .= " {$andOr} (
							( t{$field_id}_{$this->_key}.value_from + 0 ) <= '{$value_from}'
							AND
							( t{$field_id}_{$this->_key}.value_to + 0 ) >= '{$value_to}'
						)";
					}
				}
			}
			
			return true;
		}
		
				
		/**
		 * Appends data into the XML tree of a Data Source
		 *
		 * @param $wrapper
		 * @param $data
		 */
		 
		public function appendFormattedElement(&$wrapper, $data, $encode=false) {
			$value = $data['value'];
			if($this->get('range') == 1) {
				$element = new XMLElement($this->get('element_name'), null, array('range'=>'yes', 'from'=>$data['value_from'], 'to'=>$data['value_to']));
			} else {
				$element = new XMLElement($this->get('element_name'), $data['value'], array('range'=>'no'));
			}
			$wrapper->appendChild($element);
		}
		
		
			
		/* ********* UI *********** */


		/**
		 *
		 * Builds the UI for the publish page
		 *
		 * @param XMLElement $wrapper
		 * @param mixed $data
		 * @param mixed $flagWithError
		 * @param string $fieldnamePrefix
		 * @param string $fieldnamePostfix
		 */
		 
		public function displayPublishPanel(&$wrapper, $data=NULL, $flagWithError=NULL, $fieldnamePrefix=NULL, $fieldnamePostfix=NULL){

			$value = General::sanitize($data['value']);
			if(empty($value))
			{
				$value = $this->get('start_value');
			}
			
			$label = Widget::Label($this->get('label'));
			$label->appendChild(new XMLElement('i', 'Value', array('class'=>'slider-field-label-value')));
			$label->appendChild(Widget::Input('fields'.$fieldnamePrefix.'['.$this->get('element_name').']'.$fieldnamePostfix, (strlen($value) != 0 ? $value : NULL), 'text', array(
				'readonly'=>'readonly',
				'data-min-range'=>$this->get('min_range'),
				'data-max-range'=>$this->get('max_range'),
				'data-range'=>$this->get('range'),
				'data-increment-value'=>$this->get('increment_value')
			)));
			$label->appendChild(new XMLElement('div', '', array('id'=>'noUi-slider-'.$this->get('id'))));
			
			// In case of an error:
			if($flagWithError != NULL) $wrapper->appendChild(Widget::wrapFormElementWithError($label, $flagWithError));
			else $wrapper->appendChild($label);
		}
		
		
		
		/**
		 *
		 * Builds the UI for the field's settings when creating/editing a section
		 *
		 * @param XMLElement $wrapper
		 * @param array $errors
		 */
		 
		public function displaySettingsPanel(&$wrapper, $errors = null) {
			
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

		
		
		/**
		 *
		 * Create Table
		 *
		 */
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

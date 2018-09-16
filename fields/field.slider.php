<?php

	if(!defined('__IN_SYMPHONY__')) die('<h2>Symphony Error</h2><p>You cannot directly access this file</p>');

	Class fieldSlider extends Field {
		
		
		/**
		 *
		 * Constructor for the Field object
		 */
		 
		public function __construct() {
			parent::__construct();
			$this->_name = __('Slider');
			$this->set('location', 'sidebar');
		}
		
		
		
	/*-------------------------------------------------------------------------------------------------
		SETUP
	-------------------------------------------------------------------------------------------------*/
		

		public function createTable() {
			try {
				Symphony::Database()->query(sprintf("
						CREATE TABLE IF NOT EXISTS `tbl_entries_data_%d` (
						  `id` int(11) unsigned NOT NULL auto_increment,
						  `entry_id` int(11) unsigned NOT NULL,
						  `value` varchar(255) default NULL,
						  `value_from` varchar(255) default NULL,
						  `value_to` varchar(255) default NULL,
						  PRIMARY KEY  (`id`),
						  KEY `entry_id` (`entry_id`),
						  KEY `value` (`value`)
						) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
					", $this->get('id')
				));
				return true;
			}
			catch (Exception $ex) {
				return false;
			}
		}
		
		public function canFilter() {
			return true;
		}
		
		public function allowDatasourceParamOutput() {
			return true;
		}
		
		public function fetchFilterableOperators() {
			return array(
				array(
					'title' 			=> 'is',
					'filter' 			=> ' ',
					'help' 				=> __('Find values that are an exact match for the given number(s).')
				),
				array(
					'title'				=> 'less than',
					'filter'			=> 'less than ',
					'help'				=> __('Less than %s', array('<code>$x</code>'))
				),
				array(
					'title'				=> 'greater than',
					'filter'			=> 'greater than ',
					'help'				=> __('Greater than %s', array('<code>$x</code>'))
				),
				array(
					'title'				=> 'between',
					'filter'			=> '',
					'help'				=> __('Find values between two values with %s to %s', array(
						'<code>$x</code>',
						'<code>$y</code>'
					))
				),
			);
		}
		
		public function fetchSuggestionTypes() {
			return array('static');
		}
		
		
	/*-------------------------------------------------------------------------------------------------
		SETTINGS
	-------------------------------------------------------------------------------------------------*/
	
	
		/**
		 * Displays settings panel in section editor.
		 *
		 * https://www.getsymphony.com/learn/api/2.3.0/toolkit/field/#displaySettingsPanel
		 *
		 * @param XMLElement $wrapper - parent element wrapping the field
		 * @param array $errors - array with field errors, $errors['name-of-field-element']
		 */
		 
		public function displaySettingsPanel(XMLElement &$wrapper, $errors = NULL) {
			
			parent::displaySettingsPanel($wrapper, $errors);
			
			/* Field Sort Order */
			$sortorder = $this->get('sortorder');
			
			/* Fieldset & Group */
			$fieldset = new XMLElement('fieldset');
			$group = new XMLElement('div', NULL, array('class' => 'two columns'));

			/* Minimum Value */
			$min_label = Widget::Label(__('Minimum value'));
			$min_label->setAttribute('class', 'column');
			$min_label->appendChild(Widget::Input('fields['.$sortorder.'][min_range]', $this->get('min_range')));
			if (isset($errors['min_range'])) {
				$group->appendChild(Widget::Error($min_label, $errors['min_range']));
			} else {
				$group->appendChild($min_label);
			}
			
			/* Maximum Value */
			$max_label = Widget::Label(__('Maximum value'));
			$max_label->setAttribute('class', 'column');
			$max_label->appendChild(Widget::Input('fields['.$sortorder.'][max_range]', $this->get('max_range')));
			if (isset($errors['max_range'])) {
				$group->appendChild(Widget::Error($max_label, $errors['max_range']));
			} else {
				$group->appendChild($max_label);
			}

			/* Start Value */
			$start_label = Widget::Label(__('Start Value'));
			$start_label->setAttribute('class', 'column');
			$start_label->appendChild(Widget::Input('fields['.$sortorder.'][start_value]', $this->get('start_value')));
			if (isset($errors['start_value'])) {
				$group->appendChild(Widget::Error($start_label, $errors['start_value']));
			} else {
				$group->appendChild($start_label);
			}
			
			/* Incremental Value */
			$inc_label = Widget::Label(__('Incremental value'));
			$inc_label->setAttribute('class', 'column');
			$inc_label->appendChild(Widget::Input('fields['.$sortorder.'][increment_value]', $this->get('increment_value')));
			if (isset($errors['increment_value'])) {
				$group->appendChild(Widget::Error($inc_label, $errors['increment_value']));
			} else {
				$group->appendChild($inc_label);
			}
			
			/* Enable Range Mode */
			$range_label = Widget::Label();
			$range_label->setAttribute('class', 'column');
			$attributes = array('type'=>'checkbox', 'name'=>'fields['.$sortorder.'][range]', 'value'=>'yes');			
			if($this->get('range') == 1) {$attributes['checked'] = 'checked';}
			$range_checkbox = new XMLElement('input', ' '.__('Enable range mode <i>(Adds a second handle for selecting a value range)</i>'), $attributes);
			$range_label->appendChild($range_checkbox);
			$group->appendChild($range_label);
			
			$fieldset->appendChild($group);
			$wrapper->appendChild($fieldset);
			
			/* Fieldset (Default Settings) */
			$fieldset = new XMLElement('fieldset');
			$this->appendShowColumnCheckbox($fieldset);
			$wrapper->appendChild($fieldset);
			
		}
	
	
		/**
		 *
		 * Validate the fields settings and return errors if wrong or missing input is detected
		 *
		 * https://www.getsymphony.com/learn/api/2.3/toolkit/field/#checkFields
		 *
		 * @param array $errors
		 * @param boolean $checkForDuplicates
		 */	
		
		public function checkFields(array &$errors, $checkForDuplicates = true) {
			
			if(!is_array($errors)) $errors = array();
			
			$check['min_range'] = $this->get('min_range');
			$check['max_range'] = $this->get('max_range');
			$check['start_value'] = $this->get('start_value');
			$check['increment_value'] = $this->get('increment_value');
			
			// Validate Minimum Value
			if($check['min_range'] == '') {
				$errors['min_range'] = __('Minimum Value must not be empty. Please fill in a natural number.');
			} else if (!preg_match('/^[0-9]+$/', $check['min_range'])) {
				$errors['min_range'] = __('Minimum Value must be a natural number.');
			}
			
			// Validate Maximum Value
			if($check['max_range'] == '') {
				$errors['max_range'] = __('Maximum Value must not be empty. Please fill in a natural number.');
			} else if (!preg_match('/^[0-9]+$/', $check['max_range'])) {
				$errors['max_range'] = __('Maximum Value must be a natural number.');
			}
			
			// Validate Start Value
			if($check['start_value'] != '' && !preg_match('/^[0-9]+$/', $check['start_value'])) {
				$errors['start_value'] = __('Start Value must be a natural number.');
			}
			
			// Validate Increment Value
			if($check['increment_value'] != '' && !preg_match('/^[0-9]+$/', $check['increment_value'])) {
				$errors['increment_value'] = __('Incremental Value must be a natural number.');
			}
			
			return Field::checkFields($errors, $checkForDuplicates);
		}
		
		
		/**
		 *
		 * Save field settings into the field's table
		 *
		 * https://www.getsymphony.com/learn/api/2.3/toolkit/field/#commit
		 */
		 
		 public function commit() {
		 	
			if(!parent::commit()) return false;
			
			$id = $this->get('id');
			if($id === false) return false;
			
			$fields = array();
			$fields['field_id'] = $id;
			$fields['range'] = $this->get('range') == false ? 0 : 1;
			$fields['min_range'] = $this->get('min_range');
			$fields['max_range'] = $this->get('max_range');
			$fields['start_value'] = $this->get('start_value') == '' ? $this->get('min_range') : $this->get('start_value');
			$fields['increment_value'] = $this->get('increment_value') == '' ? '0' : $this->get('increment_value');
			
			Symphony::Database()->query("DELETE FROM `tbl_fields_".$this->handle()."` WHERE `field_id` = '$id' LIMIT 1");
			
			return Symphony::Database()->insert($fields, 'tbl_fields_' . $this->handle());			
		}
		
		
		
	/*-------------------------------------------------------------------------------------------------
		INPUT
	-------------------------------------------------------------------------------------------------*/
		
		
		/**
		 *
		 * Build the UI for the publish page
		 *
		 * https://www.getsymphony.com/learn/api/2.3.0/toolkit/field/#displayPublishPanel
		 *
		 * @param XMLElement $wrapper
		 * @param mixed $data
		 * @param mixed $flagWithError
		 * @param string $fieldnamePrefix
		 * @param string $fieldnamePostfix
		 */
		
		public function displayPublishPanel(XMLElement &$wrapper, $data = null, $flagWithError = null, $fieldnamePrefix = null, $fieldnamePostfix = null, $entry_id = null) {

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
		 * Process data before saving into database.
		 *
		 * https://www.getsymphony.com/learn/api/2.3/toolkit/field/#processRawFieldData
		 *
		 * @param array $data
		 * @param int $status
		 * @param $message
		 * @param boolean $simulate
		 * @param int $entry_id
		 *
		 * @return array - data to be inserted into DB
		 */
		 
		public function processRawFieldData($data, &$status, &$message = null, $simulate = false, $entry_id = null) {
			
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
		
		
	/*-------------------------------------------------------------------------------------------------
		OUTPUT
	-------------------------------------------------------------------------------------------------*/
		
		
		/**
		 *
		 * Format the field's value for display in the publish index tables.
		 *
		 * https://www.getsymphony.com/learn/api/2.3/toolkit/field/#prepareTableValue
		 *
		 * @param Array $data
		 * @param XMLElement $link (optional)
		 * @param integer $entry_id (optional)
		 *
		 * @return string
		 */
		
		public function prepareTableValue($data, XMLElement $link = null, $entry_id = null){
			
			if (empty($data)) return;
			
			// Prepare Text Value
			$value = strip_tags($data['value']);
			
			return $value;
		}
		
			
		/**
		 * Append the field's data into the XML tree of a Data Source
		 *
		 * https://www.getsymphony.com/learn/api/2.3/toolkit/field/#appendFormattedElement
		 *
		 * @param $wrapper
		 * @param $data
		 * @param $encode
		 */
		
		public function appendFormattedElement(XMLElement &$wrapper, $data, $encode = false, $mode = NULL, $entry_id = NULL) {
			$value = $data['value'];
			if($this->get('range') == 1) {
				$element = new XMLElement($this->get('element_name'), null, array('range'=>'yes', 'from'=>$data['value_from'], 'to'=>$data['value_to']));
			} else {
				$element = new XMLElement($this->get('element_name'), $data['value'], array('range'=>'no'));
			}
			$wrapper->appendChild($element);
		}
		
		
		
	/*-------------------------------------------------------------------------------------------------
		FILTERING
	-------------------------------------------------------------------------------------------------*/
		
		
		/**
		 * Build SQL for fetching the data from the DB
		 *
		 * https://www.getsymphony.com/learn/api/2.3/toolkit/field/#buildDSRetrievalSQL
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
	}

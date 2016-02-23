<?php
	
	Class extension_Slider extends Extension {
	
	
		/**
		 * Subscribe to Delegates
		 */
		public function getSubscribedDelegates() {
			return array(
				array(
					'page' => '/backend/',
					'delegate' => 'InitaliseAdminPageHead',
					'callback' => 'appendAssets'
				)
			); 
		}
		
		
		/**
		 * Append Assets
		 */
		public function appendAssets(Array $context) {
			
			$callback = Administration::instance()->getPageCallback();
			
			if(in_array($callback['context']['page'], array('new', 'edit'))) {
				Administration::instance()->Page->addStylesheetToHead(URL . '/extensions/slider/assets/nouislider.css');
				Administration::instance()->Page->addStylesheetToHead(URL . '/extensions/slider/assets/slider.css');
				Administration::instance()->Page->addScriptToHead(URL . '/extensions/slider/assets/nouislider.min.js');
				Administration::instance()->Page->addScriptToHead(URL . '/extensions/slider/assets/slider.js');
			}
		}
		
				
		/**
		 * Installation
		 */
		public function install() {
			Symphony::Database()->query("CREATE TABLE IF NOT EXISTS `tbl_fields_slider` (
				`id` int(11) unsigned NOT NULL auto_increment,
				`field_id` int(11) unsigned NOT NULL,
				`min_range` INT NOT NULL,
				`max_range` INT NOT NULL,
				`start_value` INT NOT NULL,
				`increment_value` VARCHAR(255) NOT NULL,
				`range` BOOL NOT NULL,
				PRIMARY KEY  (`id`),
				KEY `field_id` (`field_id`)
			)");
			return true;
		}
		
		
		/**
		 * Uninstall
		 */
		public function uninstall() {
			Symphony::Database()->query("DROP TABLE `tbl_fields_slider`;");
			return true;
		}
	
	}

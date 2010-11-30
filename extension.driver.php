<?php
Class extension_Slider extends Extension
{
	// About this extension:
	public function about()
	{
		return array(
			'name' => 'Field: Slider',
			'version' => '0.1',
			'release-date' => '2010-11-30',
			'author' => array(
				'name' => 'Giel Berkers',
				'website' => 'http://www.gielberkers.com',
				'email' => 'info@gielberkers.com'),
			'description' => 'Defines a slider with a range'
		);
	}
	
	// Set the delegates:
	public function getSubscribedDelegates()
	{
		// No delegates
		return array();
	}
	
	
	// Installation:
	public function install()
	{
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
	
	public function uninstall()
	{
		Symphony::Database()->query("DROP TABLE `tbl_fields_slider`;");
		return true;
	}
}
?>
<?php
/**
 * IP2Country detection.
 * 
 * @author gizmore
 * @since 2.0
 * @version 5.0
 *
 */
final class Module_IP2Country extends GWF_Module
{
	public $module_priority = 80; # Install and load late :)
	
	##############
	### Module ###
	##############
	public function getClasses() { return ['GWF_IPCountry']; }
	public function href_administrate_module() { return href('IP2Country', 'InstallIP2C'); }
	
	##############
	### Config ###
	##############
	public function getConfig()
	{
		return array(
			GDO_Checkbox::make('autodetect_signup')->initial('1'),
		);
	}
	public function cfgAutodetectSignup() { return $this->getConfigValue('autodetect_signup'); }
	
	#############
	### Hooks ###
	#############
	public function hookUserActivated(array $args)
	{
		if ($this->cfgAutodetectSignup())
		{
			$this->autodetectForUser($args[0]);
		}
	}
	private static function autodetectForUser(GWF_User $user)
	{
		if (!$user->getCountryISO())
		{
			$user->saveVar('user_country', GWF_IPCountry::detectISO($user->getRegisterIP()));
		}
	}
}

<?php
/**
 * IPCountry GDO table
 * 
 * @author gizmore
 * @since 3.0
 * @version 5.0
 *
 * @see GWF_Country
 * 
 */
final class GWF_IPCountry extends GDO
{
	###########
	### GDO ###
	###########
	public static $ENGINE = self::MYISAM;
	public function gdoCached() { return false; }
	public function gdoColumns()
	{
		return array(
			GDO_Int::make('ipc_lo')->unsigned(),
			GDO_Int::make('ipc_hi')->unsigned(),
			GDO_Object::make('ip_country')->klass('GWF_Country'),
		);
	}
	
	###########
	### API ###
	###########
	/**
	 * Detect a country by IP. Return it's ISO2 code.
	 * @param string $ip
	 * @return string country iso
	 */
	public static function detectISO(string $ip)
	{
		if ($ip = ip2long($ip))
		{
			return self::table()->select('ip_country')->where("ipc_lo <= $ip AND ipc_hi >= $ip")->limit(1)->exec()->fetchValue();
		}
	}
	
	/**
	 * 
	 * @param string $ip
	 * @return GWF_Country
	 */
	public static function detect(string $ip)
	{
		if ($iso = self::detectISO($ip))
		{
			return GWF_Country::getById($iso);
		}
	}
}

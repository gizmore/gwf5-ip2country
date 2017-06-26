<?php
final class IP2Country_InstallIP2C extends GWF_MethodForm
{
	public function createForm(GWF_Form $form)
	{
		$form->addField(GDO_AntiCSRF::make());
		$form->addField(GDO_Submit::make());
	}

	public function formValidated(GWF_Form $form)
	{
		GWF_IPCountry::table()->truncate();
		$module = Module_IP2Country::instance();
		$filename = $module->filePath('data/IpToCountry.csv');
		$fh = fopen($filename, 'r');
		$noCountry = ['ZZ','EU'];
		while ($row = fgetcsv($fh))
		{
			list($lo, $hi, $registrar, $timestamp, $iso2, $iso3, $country) = $row;
			if (!in_array($iso2, $noCountry, true))
			{
				GWF_IPCountry::blank(['ipc_lo' => $lo, 'ipc_hi' => $hi, 'ip_country' => strtolower($iso2)])->insert();
			}
		}
		
		$rows = GWF_IPCountry::table()->countWhere();
		
		return $this->message('msg_ip2country_installed', [$rows]);
	}
}

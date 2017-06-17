<?php
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

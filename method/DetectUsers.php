<?php
final class IP2Country_DetectUsers extends GWF_MethodForm
{
	public function createForm(GWF_Form $form)
	{
		$form->addField(GDO_AntiCSRF::make());
		$form->addField(GDO_Submit::make());
	}

	public function formValidated(GWF_Form $form)
	{
		$table = GWF_User::table();
		$result = $table->select()->where('user_country IS NULL AND user_register_ip IS NOT NULL')->exec();
		$rows = 0;
		while ($user = $table->fetch($result))
		{
			if ($country = GWF_IPCountry::detect($user->getRegisterIP()))
			{
				$user->saveValue('user_country', $country);
				$rows++;
			}
		}
		return $this->message('msg_ip2country_detection', [$rows]);
	}
}

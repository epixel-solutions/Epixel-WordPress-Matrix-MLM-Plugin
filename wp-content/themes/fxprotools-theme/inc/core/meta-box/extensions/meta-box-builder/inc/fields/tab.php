<?php

class MBB_Tab extends MBB_Field
{
	public $basic = array(
		'id', 'label', 'icon'
	);

	public $advanced = null;

	public function __construct()
	{
		$dashicons = mbb_get_dashicons();

		$select_icon = '<div class="icon-panel">';

		foreach ( $dashicons as $icon )
		{
			$select_icon .= '<label class="icon-single {{active.icon == \'' . $icon . '\'}}">
				<i class="wp-menu-image dashicons-before '.$icon.'"></i>
				<input type="radio" ng-model="active.icon" value="' . $icon . '" class="hidden">
			</label>';
		}

		$select_icon .= '</div>';

		$this->basic = array(
			'id', 'label', 'icon', null,
			'select_icon' => array(
				'type' => 'custom',
				'content' => $select_icon,
				'size'	=> 'wide'
			)
		);

		parent::__construct();
	}
}

new MBB_Tab;
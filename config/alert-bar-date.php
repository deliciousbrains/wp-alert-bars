<?php
acf_add_local_field_group( array(
	'key'                   => 'group_5bf3f20e5333e',
	'title'                 => 'Alert Bar Date',
	'fields'                => array(
		array(
			'key'               => 'field_5bf3f2191b4cc',
			'label'             => 'Ends',
			'name'              => 'ends',
			'type'              => 'date_time_picker',
			'instructions'      => '',
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => array(
				'width' => '',
				'class' => '',
				'id'    => '',
			),
			'display_format'    => 'F j, Y g:i a',
			'return_format'     => 'Y-m-d H:i:s',
			'first_day'         => 1,
		),
	),
	'location'              => array(
		array(
			array(
				'param'    => 'post_type',
				'operator' => '==',
				'value'    => $post_type,
			),
		),
	),
	'menu_order'            => 0,
	'position'              => 'side',
	'style'                 => 'default',
	'label_placement'       => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen'        => '',
	'active'                => 1,
	'description'           => '',
) );
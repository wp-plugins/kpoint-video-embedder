<?php
function wpkapsulerhelper_add_admin_menu(  ) { 
	add_options_page( 'kPoint Video Embedder', 'kPoint Video Embedder', 'manage_options', 'kpoint_kapsules_helper', 'wpkapsulerhelper_options_page' );
}

function wpkapsulerhelper_settings_init(  ) { 
	register_setting( 'pluginPage', 'wpkapsulerhelper_settings' );

	add_settings_section(
		'wpkapsulerhelper_pluginPage_section', 
		__( '', 'wordpress' ), 
		'wpkapsulerhelper_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'wpkapsulerhelper_kpoint_domain', 
		__( 'kPoint Domain', 'wordpress' ), 
		'wpkapsulerhelper_kpoint_domain_render', 
		'pluginPage', 
		'wpkapsulerhelper_pluginPage_section' 
	);

	add_settings_field( 
		'wpkapsulerhelper_client_id', 
		__( 'Client Id', 'wordpress' ), 
		'wpkapsulerhelper_client_id_render', 
		'pluginPage', 
		'wpkapsulerhelper_pluginPage_section' 
	);

	add_settings_field( 
		'wpkapsulerhelper_secret_key', 
		__( 'Secret Key', 'wordpress' ), 
		'wpkapsulerhelper_secret_key_render', 
		'pluginPage', 
		'wpkapsulerhelper_pluginPage_section' 
	);

}


function wpkapsulerhelper_kpoint_domain_render(  ) { 

	$options = get_option( 'wpkapsulerhelper_settings' );
	?>
	<input size='36' type='text' name='wpkapsulerhelper_settings[wpkapsulerhelper_kpoint_domain]' value='<?php echo $options['wpkapsulerhelper_kpoint_domain']; ?>'>
	<?php

}


function wpkapsulerhelper_client_id_render(  ) { 

	$options = get_option( 'wpkapsulerhelper_settings' );
	?>
	<input size='36' type='text' name='wpkapsulerhelper_settings[wpkapsulerhelper_client_id]' value='<?php echo $options['wpkapsulerhelper_client_id']; ?>'>
	<?php

}


function wpkapsulerhelper_secret_key_render(  ) { 

	$options = get_option( 'wpkapsulerhelper_settings' );
	?>
	<input size='36' type='text' name='wpkapsulerhelper_settings[wpkapsulerhelper_secret_key]' value='<?php echo $options['wpkapsulerhelper_secret_key']; ?>'>
	<?php

}


function wpkapsulerhelper_settings_section_callback(  ) { 
    echo __( '', 'wordpress' );
}


function wpkapsulerhelper_options_page(  ) { 

	?>
	<form action='options.php' method='post'>
		
		<h2>kPoint Kapsules Helper</h2>
		
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
		
	</form>
	<?php

}

?>
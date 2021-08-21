<?php
namespace JCI\Finland\Events;

?>
<div class="wrap">
	<h1><?php esc_html_e( 'JCI Finland Events', 'jcifi' ); ?></h1>

	<form method="post" action="options.php">
	    <?php
			settings_fields( settings_group() );
			do_settings_sections( settings_page_slug() );
			submit_button();
		?>
	</form>
	
</div>

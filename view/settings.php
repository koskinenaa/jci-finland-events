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

	<hr>

	<form action="admin-post.php" method="post">
		<?php if ( $data['events_fetched'] ) : ?>
			<p>
				<?php
					printf(
						_x(
							'Events fetched last %s and %d found.',
							'Api query datetime and event count',
							'jcifi'
						),
						$data['events_fetched'],
						$data['event_count']
					);
				?>
			</p>
		<?php else : ?>
			<p><?php esc_html_e('No events in the database.', 'jcifi'); ?></p>
		<?php endif; ?>
		<input type="hidden" name="action" value="populate_database">
		<?php wp_nonce_field( 'jcifi_populate_database', 'jcifi_populate_database_nonce', true, true ); ?>
		<button class="button" type="submit"><?php esc_html_e('Refresh events database', 'jcifi'); ?></button>
	</form>
</div>

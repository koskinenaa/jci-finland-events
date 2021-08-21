<?php
namespace JCI\Finland\Events;
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Edit Event', 'jcifi' ); ?></h1>

	<article>
		<?php
			event_title( $data['event'] );
			event_organizer( $data['event'] );
			event_region( $data['event'] );
			event_organization( $data['event'] );
			event_dates( $data['event'] );
			event_venue( $data['event'] );
			event_address( $data['event'] );
			event_description( $data['event'] );
			event_link( $data['event'] );
		?>

	</article>

</div>

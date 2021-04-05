<?php
namespace JCI\Finland\Events;

do_action('jcifi_event_before', $data['event']);

?>
<article class="<?php event_classes($data['event']); ?>">

	<?php do_action('jcifi_event_top', $data['event']); ?>

	<?php if ( apply_filters( 'jcifi_event_header_enabled', true, $data['event'] ) ) : ?>

		<header class="event__header">

			<?php do_action('jcifi_event_header', $data['event']); ?>

		</header>

	<?php endif; ?>

	<?php if ( apply_filters( 'jcifi_event_body_enabled', true, $data['event'] ) ) : ?>

		<div class="event__body">

			<?php do_action('jcifi_event_body', $data['event']); ?>

		</div>

	<?php endif; ?>

	<?php if ( apply_filters( 'jcifi_event_footer_enabled', true, $data['event'] ) ) : ?>

		<footer class="event__footer">

			<?php do_action('jcifi_event_footer', $data['event']); ?>

		</footer>

	<?php endif; ?>

	<?php do_action('jcifi_event_bottom', $data['event']); ?>

</article>

<?php do_action('jcifi_event_after', $data['event']); ?>

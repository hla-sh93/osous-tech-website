<?php
/**
 * The card stack.
 *
 * Each card wraps itself in the item the scroll script (assets/js/overlapping-cards.js)
 * moves; the wrapper's name reaches the cards as block context.
 *
 * @package Osous
 */

?>
<section class="section overlapping-cards">
	<div class="flex-v h-100 a-c-c">
		<div class="container">
			<div class="grid-2">
				<div class="cell self-center why-us-heading">
					<h2 class="h2 c-on-secondary"><?php echo osous_inline( $attributes['title'] ); ?></h2>
				</div>
				<div class="cards-overlapping-wrapper"><?php osous_inner( $content ); ?></div>
			</div>
		</div>
	</div>
</section>

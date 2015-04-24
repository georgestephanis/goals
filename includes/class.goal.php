<?php

namespace GeorgeStephanis\Goals;

class Goal {

	var $post;

	public function __construct( $post ) {
		$post = get_post( $post );

		if ( empty( $post ) || ! is_a( $post, 'WP_Post' ) ) {
			return new WP_Error( 'not_a_post', __( 'This is not a post, let alone a goal.', 'goals' ), $post );
		}

		if ( Goals::POST_TYPE !== $post->post_type ) {
			return new WP_Error( 'not_a_goal', __( 'This is not a goal.', 'goals' ), $post );
		}

		$this->post = $post;

		return $this;
	}

	public static function goal_details_meta_box( $post ) {
		$post = get_post( $post );

		wp_nonce_field( '_save_goal_nonce', '_save_goal_nonce', false );
		?>
		<table class="form-table">
			<?php $_goal_status = (string) get_post_meta( $post->ID, '_goal_status', true ); ?>
			<tr valign="top">
				<th scope="row"><?php esc_html_e( 'Goal Status:', 'goals' ); ?></th>
				<td>
					<label>
						<input type="radio" name="_goal_status" value="" <?php checked( $_goal_status, '' ); ?> />
						<?php echo esc_html_x( 'Not Done', 'Goal Status', 'goals' ); ?>
					</label><br />
					<label>
						<input type="radio" name="_goal_status" value="half" <?php checked( $_goal_status, 'half' ); ?> />
						<?php echo esc_html_x( 'Halfway There…', 'Goal Status', 'goals' ); ?>
					</label><br />
					<label>
						<input type="radio" name="_goal_status" value="done" <?php checked( $_goal_status, 'done' ); ?> />
						<?php echo esc_html_x( 'Done!', 'Goal Status', 'goals' ); ?>
					</label>
				</td>
			</tr>
		</table>
		<?php
	}

	public static function save_post( $post_id, $post ) {
		if ( ! wp_verify_nonce( $_POST['_save_goal_nonce'], '_save_goal_nonce' ) ) {
			return $post_id;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		if ( Goals::POST_TYPE !== $post->post_type ) {
			return $post_id;
		}

		if ( isset( $_POST['_goal_status'] ) ) {
			if ( ! in_array( $_POST['_goal_status'], array( 'half', 'done' ) ) ) {
				$_POST['_goal_status'] = '';
			}
			update_post_meta( $post_id, '_goal_status', $_POST['_goal_status'] );
		}

		return $post_id;
	}

	public function render( $args = array() ) {
		$defaults = array(
			'show_title'     => true,
			'show_thumbnail' => true,
			'comments_link'  => false,
		);
		$args = wp_parse_args( $args, $defaults );
		$goal = $this->post;
		$id   = $goal->post_ID;

		setup_postdata( $goal );
		?>
		<div class="goal goal-display" data-id="<?php the_ID(); ?>"
					data-status="<?php echo esc_attr( get_post_meta( $id, '_goal_status', true ) ); ?>">

					<?php if ( $args['show_title'] && get_the_title() ) : ?>
				<h3><?php the_title(); ?></h3>
			<?php endif; ?>

			<figure>

					<?php if ( $args['show_thumbnail'] ) : ?>
					<?php the_post_thumbnail(); ?>
				<?php endif; ?>

				<div id="goal-content"><?php echo get_post_meta( $id, '_goal_content', true ); ?></div>

				<?php if ( $caption = get_post_meta( $id, '_goal_caption', true ) ) : ?>
					<figcaption><?php echo $caption; ?></figcaption>
				<?php endif; ?>

			</figure>

			<?php if ( $notes = get_post_meta( $id, '_goal_notes', true ) ) : ?>
				<footer class="notes"><?php echo $notes; ?></footer>
			<?php endif; ?>

		</div>
		<?php
		wp_reset_postdata();
	}
}

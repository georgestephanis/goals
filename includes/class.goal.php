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

			<?php $_goal_content = get_post_meta( $post->ID, '_goal_content', true ); ?>
			<tr valign="top">
				<th scope="row"><label for="_goal_content"><?php esc_html_e( 'Goal Content:', 'goals' ); ?></label></th>
				<td>
					<textarea name="_goal_content" class="widefat"><?php echo esc_textarea( $_goal_content ); ?></textarea>
				</td>
			</tr>

			<?php $_goal_caption = get_post_meta( $post->ID, '_goal_caption', true ); ?>
			<tr valign="top">
				<th scope="row"><label for="_goal_caption"><?php esc_html_e( 'Goal Caption:', 'goals' ); ?></label></th>
				<td>
					<textarea name="_goal_caption" class="widefat"><?php echo esc_textarea( $_goal_caption ); ?></textarea>
				</td>
			</tr>

			<?php $_goal_notes = get_post_meta( $post->ID, '_goal_notes', true ); ?>
			<tr valign="top">
				<th scope="row"><label for="_goal_notes"><?php esc_html_e( 'Goal Notes:', 'goals' ); ?></label></th>
				<td>
					<textarea name="_goal_notes" class="widefat"><?php echo esc_textarea( $_goal_notes ); ?></textarea>
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

		if ( isset( $_POST['_goal_content'] ) ) {
			update_post_meta( $post_id, '_goal_content', sanitize_text_field( $_POST['_goal_content'] ) );
		}

		if ( isset( $_POST['_goal_caption'] ) ) {
			update_post_meta( $post_id, '_goal_caption', sanitize_text_field( $_POST['_goal_caption'] ) );
		}

		if ( isset( $_POST['_goal_notes'] ) ) {
			update_post_meta( $post_id, '_goal_notes', sanitize_text_field( $_POST['_goal_notes'] ) );
		}

		return $post_id;
	}

	public static function shortcode( $atts ) {
		$defaults = array(
			'id' => 0,
		);
		$atts = shortcode_atts( $defaults, $atts, 'goal' );

		$goal = new Goal( $atts['id'] );
		if ( $goal && ! is_wp_error( $goal ) ) {
			ob_start();
			$goal->render( $atts );
			return ob_get_clean();
		}
	}

	public function render( $args = array() ) {
		$defaults = array(
			'show_title'     => true,
			'show_thumbnail' => true,
			'comments_link'  => false,
		);
		$args = wp_parse_args( $args, $defaults );
		$goal = $this->post;
		$id   = $goal->ID;
		?>
		<div class="goal goal-display" data-id="<?php echo esc_attr( $id ); ?>"
					data-status="<?php echo esc_attr( get_post_meta( $id, '_goal_status', true ) ); ?>">

			<?php if ( $args['show_title'] && $goal->post_title ) : ?>
				<h3><?php echo esc_html( wptexturize( $goal->post_title ) ); ?></h3>
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
	}
}

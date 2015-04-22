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

		wp_nonce_field( '_redirect_url_nonce', '_redirect_url_nonce', false );
		$_goal_status = get_post_meta( $post->ID, '_goal_status', true );
		?>
		<table class="form-table">
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

}

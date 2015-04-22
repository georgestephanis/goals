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

}

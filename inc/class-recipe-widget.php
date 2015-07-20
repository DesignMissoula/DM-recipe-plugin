<?php

// http://www.wpbeginner.com/wp-tutorials/how-to-create-a-custom-wordpress-widget/

// Creating the widget
class Recipe_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'recipe_widget',

			// Widget name will appear in UI
			__('Recipe Widget', 'recipe_widget_domain'),

			// Widget description
			array( 'description' => __( 'Sample widget for showing recipes in sidebar', 'recipe_widget_domain' ), )
		);
	}

	// Creating widget front-end
	// This is where the action happens
	public function widget( $args, $instance ) {
		 global $post; 
		$title = apply_filters( 'widget_title', $instance['title'] );
		$fruit = apply_filters( 'widget_title', $instance['fruit'] );
		
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];

		// This is where you run the code and display the output
		// echo __( 'Hello, World!', 'recipe_widget_domain' );
		
		if ( ! empty( $fruit ) ){
				$fruits_query = get_posts(array(
		                                'post_type' => 'recipe',
		                                'posts_per_page' => 1,
		                                'orderby' => 'rand',
		                                'tax_query' => array(array(
		                                    'taxonomy' => 'fruits',
		                                    'field' => 'slug',
											'terms' => $fruit
		                                ))));
				?>
				<div class="small-12 columns">
				<ul class="small-block-grid-1 medium-block-grid-2 large-block-grid-2">
				<?php foreach ( $fruits_query as $post ) : setup_postdata( $post ); ?>
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'medium', array('class' => 'th img-responsive') ); ?>
					<?php endif; ?>	
					<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
					<?php // the_excerpt(); ?>
					<a href="<?php the_permalink(); ?>" class="view-more"><?php _e('View full recipe &rsaquo;'); ?></a>
				<?php 
					endforeach; 
					wp_reset_postdata();	
		}

		echo $args['after_widget'];
	}

	// Widget Backend
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'recipe_widget_domain' );
		}
		
		if ( isset( $instance[ 'fruit' ] ) ) {
			$fruit = $instance[ 'fruit' ];
		}
		else {
			$fruit = __( 'apple', 'recipe_widget_domain' );
		}
		// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_id( 'fruit' ); ?>"><?php _e( 'Fruit:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'fruit' ); ?>" name="<?php echo $this->get_field_name( 'fruit' ); ?>" type="text" value="<?php echo esc_attr( $fruit ); ?>" />
</p>

<?php
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['fruit'] = ( ! empty( $new_instance['fruit'] ) ) ? strtolower(strip_tags( $new_instance['fruit'] )) : '';
		return $instance;
	}
} // Class recipe_widget ends here

// Register and load the widget
function recipe_load_widget() {
	register_widget( 'Recipe_Widget' );
}
add_action( 'widgets_init', 'recipe_load_widget' );

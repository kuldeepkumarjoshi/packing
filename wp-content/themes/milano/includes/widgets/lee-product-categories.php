<?php
if(!class_exists('WC_Widget')){
	return;
}

add_action( 'widgets_init', 'lee_product_categories_widget' );

function lee_product_categories_widget() {
	register_widget( 'Lee_Product_Categories_Widget' );
}

class Lee_Product_Categories_Widget extends WC_Widget{

	/**
	 * Category ancestors
	 *
	 * @var array
	 */
	public $cat_ancestors;

	/**
	 * Current Category
	 *
	 * @var bool
	 */
	public $current_cat;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    = 'woocommerce widget_product_categories';
		$this->widget_description = __( 'Display product categories with Accordion or List type.', 'woocommerce' );
		$this->widget_id          = 'lee_product_categories';
		$this->widget_name        = __( 'Leetheme Product Categories', 'woocommerce' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => __( 'Product Categories', 'woocommerce' ),
				'label' => __( 'Title', 'woocommerce' )
			),
			'orderby' => array(
				'type'  => 'select',
				'std'   => 'name',
				'label' => __( 'Order by', 'woocommerce' ),
				'options' => array(
					'order' => __( 'Category Order', 'woocommerce' ),
					'name'  => __( 'Name', 'woocommerce' )
				)
			),
			'count' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Show product counts', 'woocommerce' )
			),
			'hierarchical' => array(
				'type'  => 'checkbox',
				'std'   => 1,
				'label' => __( 'Show hierarchy', 'woocommerce' )
			),
			'show_children_only' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Only show children of the current category', 'woocommerce' )
			),
			'accordion' => array(
				'type'  => 'checkbox',
				'std'   => 1,
				'label' => __( 'Show as Accordion', 'woocommerce' )
			)
		);
		parent::__construct();
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 *
	 * @param array $args
	 * @param array $instance
	 *
	 * @return void
	 */
	public function widget( $args, $instance ) {
		global $wp_query, $post;

		$a             = isset( $instance['accordion'] ) ? $instance['accordion'] : $this->settings['accordion']['std'];
		$c             = isset( $instance['count'] ) ? $instance['count'] : $this->settings['count']['std'];
		$h             = isset( $instance['hierarchical'] ) ? $instance['hierarchical'] : $this->settings['hierarchical']['std'];
		$s             = isset( $instance['show_children_only'] ) ? $instance['show_children_only'] : $this->settings['show_children_only']['std'];
		$o             = isset( $instance['orderby'] ) ? $instance['orderby'] : $this->settings['orderby']['std'];
		$list_args     = array( 'show_count' => $c, 'hierarchical' => $h, 'taxonomy' => 'product_cat', 'hide_empty' => false );

		// Menu Order
		$list_args['menu_order'] = false;
		if ( $o == 'order' ) {
			$list_args['menu_order'] = 'asc';
		} else {
			$list_args['orderby']    = 'title';
		}

		// Setup Current Category
		$this->current_cat   = false;
		$this->cat_ancestors = array();

		if ( is_tax( 'product_cat' ) ) {

			$this->current_cat   = $wp_query->queried_object;
			$this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'product_cat' );

		} elseif ( is_singular( 'product' ) ) {

			$product_category = wc_get_product_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent' ) );

			if ( $product_category ) {
				$this->current_cat   = end( $product_category );
				$this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'product_cat' );
			}

		}

		// Show Siblings and Children Only
		if ( $s && $this->current_cat ) {

			// Top level is needed
			$top_level = get_terms(
				'product_cat',
				array(
					'fields'       => 'ids',
					'parent'       => 0,
					'hierarchical' => true,
					'hide_empty'   => false
				)
			);

			// Direct children are wanted
			$direct_children = get_terms(
				'product_cat',
				array(
					'fields'       => 'ids',
					'parent'       => $this->current_cat->term_id,
					'hierarchical' => true,
					'hide_empty'   => false
				)
			);

			// Gather siblings of ancestors
			$siblings  = array();
			if ( $this->cat_ancestors ) {
				foreach ( $this->cat_ancestors as $ancestor ) {
					$ancestor_siblings = get_terms(
						'product_cat',
						array(
							'fields'       => 'ids',
							'parent'       => $ancestor,
							'hierarchical' => false,
							'hide_empty'   => false
						)
					);
					$siblings = array_merge( $siblings, $ancestor_siblings );
				}
			}

			if ( $h ) {
				$include = array_merge( $top_level, $this->cat_ancestors, $siblings, $direct_children, array( $this->current_cat->term_id ) );
			} else {
				$include = array_merge( $direct_children );
			}

			//$dropdown_args['include'] = implode( ',', $include );
			$list_args['include']     = implode( ',', $include );

			if ( empty( $include ) ) {
				return;
			}

		}

		$this->widget_start( $args, $instance );

		include_once( WC()->plugin_path() . '/includes/walkers/class-product-cat-list-walker.php' );

		$list_args['walker']                     = new WC_Product_Cat_List_Walker;
		$list_args['title_li']                   = '';
		$list_args['pad_counts']                 = 1;
		$list_args['show_option_none']           = __('No product categories exist.', 'woocommerce' );
		$list_args['current_category']           = ( $this->current_cat ) ? $this->current_cat->term_id : '';
		$list_args['current_category_ancestors'] = $this->cat_ancestors;
		$accordion = '';
		if($a) $accordion = ' lee_accordion';
		echo '<div class="widget woocommerce widget_product_categories"><ul class="product-categories'.$accordion.'">';
		wp_list_categories( apply_filters( 'woocommerce_product_categories_widget_args', $list_args ) );
		echo '</ul></div>';

		$this->widget_end( $args );
	}



}
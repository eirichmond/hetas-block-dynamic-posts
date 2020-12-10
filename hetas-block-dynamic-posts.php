<?php
/**
 * Plugin Name:     HETAS Block Dynamic Posts
 * Description:     A block to handle HETAS latests posts
 * Version:         0.1.0
 * Author:          Elliott Richmond
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     hetas-block-dynamic-posts
 *
 * @package         hetas-block-dynamic-posts
 */

/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
function hetas_block_dynamic_posts_hetas_block_dynamic_posts_block_init() {
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/index.asset.php";
	if ( ! file_exists( $script_asset_path ) ) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "hetas-block-dynamic-posts/hetas-block-dynamic-posts" block first.'
		);
	}
	$index_js     = 'build/index.js';
	$script_asset = require( $script_asset_path );
	wp_register_script(
		'hetas-block-dynamic-posts-hetas-block-dynamic-posts-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version']
	);
	wp_set_script_translations( 'hetas-block-dynamic-posts-hetas-block-dynamic-posts-block-editor', 'hetas-block-dynamic-posts' );

	$editor_css = 'build/index.css';
	wp_register_style(
		'hetas-block-dynamic-posts-hetas-block-dynamic-posts-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	$style_css = 'build/style-index.css';
	wp_register_style(
		'hetas-block-dynamic-posts-hetas-block-dynamic-posts-block',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	register_block_type( 'hetas-block-dynamic-posts/hetas-block-dynamic-posts', array(
		'editor_script' => 'hetas-block-dynamic-posts-hetas-block-dynamic-posts-block-editor',
		'editor_style'  => 'hetas-block-dynamic-posts-hetas-block-dynamic-posts-block-editor',
		'style'         => 'hetas-block-dynamic-posts-hetas-block-dynamic-posts-block',
		'render_callback' => 'hetas_block_dynamic_posts_callback',
		'attributes' => array(
			'postsPerPage' => array(
				'type' =>'number',
				'default' => 3,
			),
			'myRichHeading' => array(
				'type' => 'string',
			),
			'myRichText' => array(
				'type' => 'string',
				'source' => 'html',
				'selector' => 'p'
			),
			'toggle' => array(
				'type' => 'boolean',
				'default' => true
			),
			'favoriteAnimal' => array(
				'type' => 'string',
				'default' => 'dogs'
			),
			'favoriteColor' => array(
				'type' => 'string',
				'default' => '#DDDDDD'
			),
			'activateLasers' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
	) );
}
add_action( 'init', 'hetas_block_dynamic_posts_hetas_block_dynamic_posts_block_init' );

function hetas_block_dynamic_posts_callback($attributes, $content) {
	$args = array(
		'posts_per_page' => $attributes['postsPerPage'],
		'post_status' => 'publish',
		'post_type' => 'post',
	);
	$the_query = new WP_Query($args);
	
	// The Loop
	if ( $the_query->have_posts() ) {
		$html = '<div class="latest-news-leader">Letest news from hetas</div>';
		$html .= '<div class="wp-block-hetas-block-dynamic-posts-hetas-block-dynamic-posts">';
		while ( $the_query->have_posts() ) { $the_query->the_post();
			$html .= '<article id="post-'. get_the_ID() .'" class="fpost">
			<figure>
				<div class="fimage">'. get_the_post_thumbnail(get_the_ID(), 'frontpage-post-2020').'</div>
			</figure>

			<div class="ftext">
				<header>
					<h3 class="ptitle"><a href="'.get_permalink(get_the_ID()).'" title="'.get_the_title().'">'.get_the_title().'</a></h3>
				</header>
				<div class="entry-content">
					<p class="ptext">'.get_the_excerpt(get_the_ID()).'</p>
				</div>
				<footer>
					<p class="link"><a href="'.get_permalink(get_the_ID()).'" title="'.get_the_title().'">Read more...</a></p>
				</footer>
			</div>
		</article>';
		}
		$html .=  '</div>';
		return $html;
	} else {
		return;
	}


}
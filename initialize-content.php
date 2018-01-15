<?php
/**
 * Php script that generates default content for a new planet4 installation.
 * It depends on wp-cli.
 */


/**
 * 1. Declare default content variables.
 */


/**
 * Pages.
 * To add a new page to default content create an entry like this:
 * 	'act'           => [
 *		'content' => 'page-act.html',
 *		'name'    => 'act',
 *		'status'  => 'publish',
 *		'title'   => 'ACT',
 *		'type'    => 'page',
 *	 ],
 */
$pages = [
	'act'           => [
		'content' => 'page-act.html',
		'name'    => 'act',
		'status'  => 'publish',
		'title'   => 'ACT',
		'type'    => 'page',
	],
	'explore'       => [
		'content' => 'page-explore.html',
		'name'    => 'explore',
		'status'  => 'publish',
		'title'   => 'EXPLORE',
		'type'    => 'page',
	],
	'news'          => [
		'content' => 'page-news.html',
		'name'    => 'news',
		'status'  => 'publish',
		'title'   => 'NEWS',
		'type'    => 'page',
	],
	'about'         => [
		'content' => 'page-about.html',
		'name'    => 'about',
		'status'  => 'publish',
		'title'   => 'ABOUT',
		'type'    => 'page',
	],
	'faq'           => [
		'content' => 'page-faq.html',
		'name'    => 'faq',
		'status'  => 'publish',
		'title'   => 'faq',
		'type'    => 'page',
	],
	'press'         => [
		'content' => 'page-press.html',
		'name'    => 'press',
		'status'  => 'publish',
		'title'   => 'press',
		'type'    => 'page',
	],
	'terms'         => [
		'content' => 'page-terms.html',
		'name'    => 'terms',
		'status'  => 'publish',
		'title'   => 'terms',
		'type'    => 'page',
	],
	'privacy'       => [
		'content' => 'page-privacy.html',
		'name'    => 'privacy',
		'status'  => 'publish',
		'title'   => 'privacy',
		'type'    => 'page',
	],
	'codeofconduct' => [
		'content' => 'page-code-of-conduct.html',
		'name'    => 'codeofconduct',
		'status'  => 'publish',
		'title'   => 'CODE OF CONDUCT',
		'type'    => 'page',
	],
	'jobs'          => [
		'content' => 'page-jobs.html',
		'name'    => 'jobs',
		'status'  => 'publish',
		'title'   => 'JOBS',
		'type'    => 'page',
	],
];

/**
 * Menus.
 */
$menus = [
	'Navigation Bar Menu' => [
		'pages' => [
			'act',
			'explore',
		],
	],
	'Footer Social'       => [
		'custom' => [
			[
				'name'    => 'Twitter',
				'url'     => 'http://twitter.com',
				'classes' => 'fa fa-twitter',
			],
			[
				'name'    => 'Facebook',
				'url'     => 'http://facebook.com',
				'classes' => 'fa fa-facebook-official',
			],
			[
				'name'    => 'Youtube',
				'url'     => 'http://youtube.com',
				'classes' => 'fa fa-youtube-play',
			],
			[
				'name'    => 'Instagram',
				'url'     => 'http://instagram.com',
				'classes' => 'fa fa-instagram',
			],
		],
	],
	'Footer Primary'      => [
		'pages' => [
			'news',
			'about',
			'jobs',
			'faq',
			'press',
			'terms'
		]
	],
	'Footer Secondary'    => [
		'pages' => [
			'privacy',
			'codeofconduct',
		]
	],
];


/**
 * 2. Create default content
 */

// Create pages.
echo "Create planet4 default pages\n";
create_pages( $pages );

// Create menus.
echo "Create planet4 default menus\n";
create_menus( $menus );

// Assign items to menus.
echo "Assign items to planet4 default menus\n";
assign_items_to_menus( $menus );


// Rest custom commands.
echo "Execute rest custom commands\n";
execute_wp_command( "wp menu location assign navigation-bar-menu navigation-bar-menu" );
execute_wp_command( "wp option update copyright \"Unless otherwise stated, the content on this website is licensed under a <a href=\"https://creativecommons.org/share-your-work/public-domain/cc0\">CC0 International License</a>\"" );
execute_wp_command( "wp option update date_format 'j F Y'" );


// Helper functions.

/**
 * Assing items to menus
 *
 * @param array menus array.
 */
function assign_items_to_menus( $menus ) {

	foreach ( $menus as $menu_name => $menu ) {
		$menu_id = get_menu_id( $menu_name );
		if ( $menu_id <= 0 ) {
			continue;
		}
		if ( isset( $menu['pages'] ) ) {

			foreach ( $menu['pages'] as $page_name ) {
				$page_id = get_page_id( $page_name );
				if ( $page_id <= 0 ) {
					continue;
				}
				if ( ! is_item_assigned_to_menu( $page_name, $menu_id ) ) {
					echo add_page_to_menu( $menu_id, $page_id );
				}
			}
		}
		if ( isset( $menu['custom'] ) ) {
			foreach ( $menu['custom'] as $custom ) {
				if ( ! is_item_assigned_to_menu( $custom['name'], $menu_id ) ) {
					echo add_custom_to_menu( $menu_id, $custom['name'], $custom['url'], $custom['classes'] );
				}
			}
		}
	}
}

/**
 * Create pages.
 *
 * @param $pages
 */
function create_pages( $pages ) {
	foreach ( $pages as $page_name => $page ) {
		if ( get_page_id( $page_name ) === 0 ) {
			echo create_page( $page );
		}
	}
}

/**
 * Create menus.
 *
 * @param $menus
 */
function create_menus( $menus ) {
	foreach ( $menus as $menu_name => $menu ) {
		if ( get_menu_id( $menu_name ) === 0 ) {
			echo create_menu( $menu_name );
		}
	}
}

/**
 * Get menu id.
 *
 * @param $title
 *
 * @return integer menu_id
 */
function get_menu_id( $title ) {
	$menus = json_decode( shell_exec( "wp menu list --format=json" ) );
	if ( $menus !== false && is_array($menus) ) {
		foreach ( $menus as $menu ) {
			if ( isset( $menu->name ) && $title == $menu->name ) {
				return intval( $menu->term_id );
			}
		}

		return 0;
	}

	return - 1;
}

function get_menu_items( $menu_id ) {
	$output = shell_exec( "wp menu item list $menu_id --fields=type,title,object_id --format=json" );
	if ( json_decode( $output ) ) {
		return array_column( json_decode( $output ), 'title' );
	}

	return [];
}

function get_page_id( $title ) {
	return intval( shell_exec( "wp post list --post_type=page --title=$title --field=ID" ) );
}

function is_item_assigned_to_menu( $item_title, $menu_id ) {
	$menu_items = get_menu_items( $menu_id );

	return in_array( strtolower( $item_title ), array_map( 'strtolower', $menu_items ) );
}

function create_page( $page ) {
	$title   = $page['title'];
	$name    = $page['name'];
	$content = $page['content'];
	$type    = $page['type'];
	$status  = $page['status'];

	return shell_exec( "wp post create --post_type=$type --post_title=\"$title\" --post_name=\"$name\" --post_status=$status \"$content\"" );
}

function create_menu( $title ) {
	return shell_exec( "wp menu create \"$title\"" );
}

function add_page_to_menu( $menu_id, $page_id ) {
	return shell_exec( "wp menu item add-post $menu_id $page_id" );
}

function add_custom_to_menu( $menu_id, $name, $url, $classes ) {
	return shell_exec( "wp menu item add-custom $menu_id \"$name\" $url --classes=\"$classes\"" );
}

function execute_wp_command( $command ) {
	return shell_exec( $command );
}
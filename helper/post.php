<?php
class Helper_Post {
	
	protected $id;
	protected $name;
	protected $pname;
	protected $supports;
	protected $position = 100;
	protected $show_menu = true;
	
	public function __construct(){
		$this->init();
	}

	public function init()
	{
		register_post_type( $this->id,
			array(
				'labels' 		=> array(
					'name'				=> __( $this->name ),
					'singular_name' 	=> __( $this->name ),
					'add_new'			=> __( "Add New {$this->name}" ),
					'all_items'			=> __( "All {$this->pname}" ),
					'add_new_item'		=> __( "Add New {$this->name}" ),
					'edit_item'			=> __( "Edit {$this->name}" ),
					'new_item'			=> __( "New {$this->name}" ),
					'view_item'			=> __( "View {$this->name}" ),
					'search_items'		=> __( "Search {$this->name}" ),
					'not_found'			=> __( 'No '.strtolower( $this->name ).' found' ),
					'not_found_in_trash'=> __( 'No '.strtolower( $this->name ).' found in Trash' )
				),
				'supports' 				=> $this->supports,
				'public' 				=> true,
				'has_archive' 			=> true,
				'rewrite' 		=> array(
					'slug' 				=> $this->id,
					'with_front'		=> true
				),
				'menu_position'			=> $this->position,
				'show_in_nav_menus'   	=> $this->show_menu,
			)
	   	);
	}
}
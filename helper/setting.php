<?php
/*
*	Main Class for the theme option
*/

class Helper_Setting {

	protected $id;
	protected $name;
	protected $defaults;
	protected $label;
	protected $options;
	protected $static_field;

	public function __construct(){
		$this->options = get_option( $this->id, array() );
		
		$this->options = array_merge( $this->defaults, $this->options );
		
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'menu' ) );
	}

	public function init(){
		
		//Register Settings
		register_setting(
			$this->id,       
			$this->id, 
			array( &$this, 'validate' )
		);
	}
	
	public function menu(){
		add_theme_page(
			__( $this->name, $this->id ),
			__( $this->name, $this->id ),
			'edit_theme_options',
			$this->id,
			array( &$this, 'page' )
		);
	}
	
	public function page(){
		?>
		<style>
			#data_wrapper{
				margin-top: 20px;
			}
			#data_wrapper label{
				display: block;	
				vertical-align: middle;
				padding: 5px 0px;
			}
			
			#data_wrapper > div{
				padding: 10px;
				border: 1px solid #ccc;
				margin-bottom: 10px;
			}
			
			#data_wrapper > div .item{
				width: 350px;
				float: left;
				padding: 10px;
			}
			
			#data_wrapper input, #data_wrapper textarea, #data_wrapper select{
				width: 100%;
			}
			
			#data_wrapper > div > h3{
				margin: 0px;
			}
			
			#data_wrapper .buttons{
				padding: 10px 10px 5px;
			}
		</style>
        <div class="wrap">
            <?php screen_icon(); ?>
       	  	<h2><?php echo  __(  $this->name, $this->id ); ?></h2>
            <?php settings_errors(); ?>
			<form method="post" action="options.php">
                <?php settings_fields( $this->id );?>
               	<div id="data_wrapper" >
                <?php $this->render_page();?>
                </div>
				<?php submit_button();?>
            </form>
		</div>
	<?php
	}
	
	public function validate( $input ){
		$output = $this->defaults;
		
		foreach( array_keys( $this->defaults ) as $index ){
			
			if( isset( $input[ $index ] ) && is_array( $input[ $index ] ) && array_search( $index, $this->static_field ) === FALSE ){
				
				$data = array();
				foreach( $input[ $index ] as $item ){
					if( !empty( $item ) ) $data[] = $item;
				}
				
				if( !empty( $data ) ) $output[ $index ] = $data;
			}
			elseif( isset( $input[ $index ] ) && !empty( $input[ $index ] ) ) 
				$output[ $index ] = $input[ $index ];
		}
		
		return $output;
	}
	
	public function render_page(){
	}
	
	public function name( $field ){
		echo "{$this->id}[{$field}]";
	}
	
	public function label( $index ){
		echo $this->label[ $index ];
	}
	
	public function value( $index, $echo = true ){
		
		if( $echo ) echo $this->options[ $index ];
		else return $this->options[ $index ];
	}
}
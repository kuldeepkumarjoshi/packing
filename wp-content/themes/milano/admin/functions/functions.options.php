<?php

add_action('init','of_options');

if (!function_exists('of_options'))
{
	function of_options()
	{
		//Access the WordPress Categories via an Array
		$of_categories 		= array();  
		$of_categories_obj 	= get_categories('hide_empty=0');
		foreach ($of_categories_obj as $of_cat) {
		    $of_categories[$of_cat->cat_ID] = $of_cat->cat_name;}
		$categories_tmp 	= array_unshift($of_categories, "Select a category:");
	       
		//Access the WordPress Pages via an Array
		$of_pages 			= array();
		$of_pages_obj 		= get_pages('sort_column=post_parent,menu_order');
		foreach ($of_pages_obj as $of_page) {
		    $of_pages[$of_page->ID] = $of_page->post_name; }
		$of_pages_tmp 		= array_unshift($of_pages, "Select a page:");       
	
		//Testing 
		$of_options_select 	= array("one","two","three","four","five"); 
		$of_options_radio 	= array("one" => "One","two" => "Two","three" => "Three","four" => "Four","five" => "Five");
		
		//Sample Homepage blocks for the layout manager (sorter)
		$of_options_homepage_blocks = array
		( 
			"disabled" => array (
				"placebo" 		=> "placebo", //REQUIRED!
				"block_one"		=> "Block One",
				"block_two"		=> "Block Two",
				"block_three"	=> "Block Three",
			), 
			"enabled" => array (
				"placebo" 		=> "placebo", //REQUIRED!
				"block_four"	=> "Block Four",
			),
		);


		//Stylesheets Reader
		$alt_stylesheet_path = LAYOUT_PATH;
		$alt_stylesheets = array();
		
		if ( is_dir($alt_stylesheet_path) ) 
		{
		    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) 
		    { 
		        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) 
		        {
		            if(stristr($alt_stylesheet_file, ".css") !== false)
		            {
		                $alt_stylesheets[] = $alt_stylesheet_file;
		            }
		        }    
		    }
		}


		//Background Images Reader
		$bg_images_path = get_stylesheet_directory(). '/images/bg/'; // change this to where you store your bg images
		$bg_images_url = get_template_directory_uri().'/images/bg/'; // change this to where you store your bg images
		$bg_images = array();
		
		if ( is_dir($bg_images_path) ) {
		    if ($bg_images_dir = opendir($bg_images_path) ) { 
		        while ( ($bg_images_file = readdir($bg_images_dir)) !== false ) {
		            if(stristr($bg_images_file, ".png") !== false || stristr($bg_images_file, ".jpg") !== false) {
		                $bg_images[] = $bg_images_url . $bg_images_file;
		            }
		        }    
		    }
		}
		

		/*-----------------------------------------------------------------------------------*/
		/* TO DO: Add options/functions that use these */
		/*-----------------------------------------------------------------------------------*/
		
		//More Options
		$uploads_arr 		= wp_upload_dir();
		$all_uploads_path 	= $uploads_arr['path'];
		$all_uploads 		= get_option('of_uploads');
		$other_entries 		= array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
		$body_repeat 		= array("no-repeat","repeat-x","repeat-y","repeat");
		$body_pos 			= array("top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right");
		
		//$google_fonts = array('Open Sans' => 'Open Sans','Sacramento' => 'Sacramento','Droid Sans' =>'Droid Sans','Oswald' => 'Oswald','Droid Serif' => 'Droid Serif','Lato' => 'Lato','Francois One' => 'Francois One','Raleway' => 'Raleway','Arvo' => 'Arvo','Roboto Slab' => 'Roboto Slab','Noto Serif' => 'Noto Serif','Noto Sans' =>'Noto Sans','Abril Fatface'=>'Abril Fatface','Clicker Script' => 'Clicker Script');
		$google_fonts = array('arial'=>'Arial',
						'verdana'=>'Verdana, Geneva',
						'trebuchet'=>'Trebuchet',
						'trebuchet ms'=>'Trebuchet MS',
						'georgia' =>'Georgia',
						'times'=>'Times New Roman',
						'tahoma'=>'Tahoma, Geneva',
						'helvetica'=>'Helvetica',
						'Abel' => 'Abel',
						'Abril Fatface' => 'Abril Fatface',
						'Aclonica' => 'Aclonica',
						'Acme' => 'Acme',
						'Actor' => 'Actor',
						'Adamina' => 'Adamina',
						'Advent Pro' => 'Advent Pro',
						'Aguafina Script' => 'Aguafina Script',
						'Aladin' => 'Aladin',
						'Aldrich' => 'Aldrich',
						'Alegreya' => 'Alegreya',
						'Alegreya SC' => 'Alegreya SC',
						'Alex Brush' => 'Alex Brush',
						'Alfa Slab One' => 'Alfa Slab One',
						'Alice' => 'Alice',
						'Alike' => 'Alike',
						'Alike Angular' => 'Alike Angular',
						'Allan' => 'Allan',
						'Allerta' => 'Allerta',
						'Allerta Stencil' => 'Allerta Stencil',
						'Allura' => 'Allura',
						'Almendra' => 'Almendra',
						'Almendra SC' => 'Almendra SC',
						'Amaranth' => 'Amaranth',
						'Amatic SC' => 'Amatic SC',
						'Amethysta' => 'Amethysta',
						'Andada' => 'Andada',
						'Andika' => 'Andika',
						'Angkor' => 'Angkor',
						'Annie Use Your Telescope' => 'Annie Use Your Telescope',
						'Anonymous Pro' => 'Anonymous Pro',
						'Antic' => 'Antic',
						'Antic Didone' => 'Antic Didone',
						'Antic Slab' => 'Antic Slab',
						'Anton' => 'Anton',
						'Arapey' => 'Arapey',
						'Arbutus' => 'Arbutus',
						'Architects Daughter' => 'Architects Daughter',
						'Arimo' => 'Arimo',
						'Arizonia' => 'Arizonia',
						'Armata' => 'Armata',
						'Artifika' => 'Artifika',
						'Arvo' => 'Arvo',
						'Asap' => 'Asap',
						'Asset' => 'Asset',
						'Astloch' => 'Astloch',
						'Asul' => 'Asul',
						'Atomic Age' => 'Atomic Age',
						'Aubrey' => 'Aubrey',
						'Audiowide' => 'Audiowide',
						'Average' => 'Average',
						'Averia Gruesa Libre' => 'Averia Gruesa Libre',
						'Averia Libre' => 'Averia Libre',
						'Averia Sans Libre' => 'Averia Sans Libre',
						'Averia Serif Libre' => 'Averia Serif Libre',
						'Bad Script' => 'Bad Script',
						'Balthazar' => 'Balthazar',
						'Bangers' => 'Bangers',
						'Basic' => 'Basic',
						'Battambang' => 'Battambang',
						'Baumans' => 'Baumans',
						'Bayon' => 'Bayon',
						'Belgrano' => 'Belgrano',
						'Belleza' => 'Belleza',
						'Bentham' => 'Bentham',
						'Berkshire Swash' => 'Berkshire Swash',
						'Bevan' => 'Bevan',
						'Bigshot One' => 'Bigshot One',
						'Bilbo' => 'Bilbo',
						'Bilbo Swash Caps' => 'Bilbo Swash Caps',
						'Bitter' => 'Bitter',
						'Black Ops One' => 'Black Ops One',
						'Bokor' => 'Bokor',
						'Bonbon' => 'Bonbon',
						'Boogaloo' => 'Boogaloo',
						'Bowlby One' => 'Bowlby One',
						'Bowlby One SC' => 'Bowlby One SC',
						'Brawler' => 'Brawler',
						'Bree Serif' => 'Bree Serif',
						'Bubblegum Sans' => 'Bubblegum Sans',
						'Buda' => 'Buda',
						'Buenard' => 'Buenard',
						'Butcherman' => 'Butcherman',
						'Butterfly Kids' => 'Butterfly Kids',
						'Cabin' => 'Cabin',
						'Cabin Condensed' => 'Cabin Condensed',
						'Cabin Sketch' => 'Cabin Sketch',
						'Caesar Dressing' => 'Caesar Dressing',
						'Cagliostro' => 'Cagliostro',
						'Calligraffitti' => 'Calligraffitti',
						'Cambo' => 'Cambo',
						'Candal' => 'Candal',
						'Cantarell' => 'Cantarell',
						'Cantata One' => 'Cantata One',
						'Cardo' => 'Cardo',
						'Carme' => 'Carme',
						'Carter One' => 'Carter One',
						'Caudex' => 'Caudex',
						'Cedarville Cursive' => 'Cedarville Cursive',
						'Ceviche One' => 'Ceviche One',
						'Changa One' => 'Changa One',
						'Chango' => 'Chango',
						'Chau Philomene One' => 'Chau Philomene One',
						'Chelsea Market' => 'Chelsea Market',
						'Chenla' => 'Chenla',
						'Cherry Cream Soda' => 'Cherry Cream Soda',
						'Chewy' => 'Chewy',
						'Chicle' => 'Chicle',
						'Chivo' => 'Chivo',
						'Coda' => 'Coda',
						'Coda Caption' => 'Coda Caption',
						'Codystar' => 'Codystar',
						'Comfortaa' => 'Comfortaa',
						'Coming Soon' => 'Coming Soon',
						'Concert One' => 'Concert One',
						'Condiment' => 'Condiment',
						'Content' => 'Content',
						'Contrail One' => 'Contrail One',
						'Convergence' => 'Convergence',
						'Cookie' => 'Cookie',
						'Copse' => 'Copse',
						'Corben' => 'Corben',
						'Cousine' => 'Cousine',
						'Coustard' => 'Coustard',
						'Covered By Your Grace' => 'Covered By Your Grace',
						'Crafty Girls' => 'Crafty Girls',
						'Creepster' => 'Creepster',
						'Crete Round' => 'Crete Round',
						'Crimson Text' => 'Crimson Text',
						'Crushed' => 'Crushed',
						'Cuprum' => 'Cuprum',
						'Cutive' => 'Cutive',
						'Damion' => 'Damion',
						'Dancing Script' => 'Dancing Script',
						'Dangrek' => 'Dangrek',
						'Dawning of a New Day' => 'Dawning of a New Day',
						'Days One' => 'Days One',
						'Delius' => 'Delius',
						'Delius Swash Caps' => 'Delius Swash Caps',
						'Delius Unicase' => 'Delius Unicase',
						'Della Respira' => 'Della Respira',
						'Devonshire' => 'Devonshire',
						'Didact Gothic' => 'Didact Gothic',
						'Diplomata' => 'Diplomata',
						'Diplomata SC' => 'Diplomata SC',
						'Doppio One' => 'Doppio One',
						'Dorsa' => 'Dorsa',
						'Dosis' => 'Dosis',
						'Dr Sugiyama' => 'Dr Sugiyama',
						'Droid Sans' => 'Droid Sans',
						'Droid Sans Mono' => 'Droid Sans Mono',
						'Droid Serif' => 'Droid Serif',
						'Duru Sans' => 'Duru Sans',
						'Dynalight' => 'Dynalight',
						'EB Garamond' => 'EB Garamond',
						'Eater' => 'Eater',
						'Economica' => 'Economica',
						'Electrolize' => 'Electrolize',
						'Emblema One' => 'Emblema One',
						'Emilys Candy' => 'Emilys Candy',
						'Engagement' => 'Engagement',
						'Enriqueta' => 'Enriqueta',
						'Erica One' => 'Erica One',
						'Esteban' => 'Esteban',
						'Euphoria Script' => 'Euphoria Script',
						'Ewert' => 'Ewert',
						'Exo' => 'Exo',
						'Exo 2' => 'Exo 2',
						'Expletus Sans' => 'Expletus Sans',
						'Fanwood Text' => 'Fanwood Text',
						'Fascinate' => 'Fascinate',
						'Fascinate Inline' => 'Fascinate Inline',
						'Federant' => 'Federant',
						'Federo' => 'Federo',
						'Felipa' => 'Felipa',
						'Fjord One' => 'Fjord One',
						'Flamenco' => 'Flamenco',
						'Flavors' => 'Flavors',
						'Fondamento' => 'Fondamento',
						'Fontdiner Swanky' => 'Fontdiner Swanky',
						'Forum' => 'Forum',
						'Fjalla One' => 'Fjalla One',
						'Francois One' => 'Francois One',
						'Fredericka the Great' => 'Fredericka the Great',
						'Fredoka One' => 'Fredoka One',
						'Freehand' => 'Freehand',
						'Fresca' => 'Fresca',
						'Frijole' => 'Frijole',
						'Fugaz One' => 'Fugaz One',
						'GFS Didot' => 'GFS Didot',
						'GFS Neohellenic' => 'GFS Neohellenic',
						'Galdeano' => 'Galdeano',
						'Gentium Basic' => 'Gentium Basic',
						'Gentium Book Basic' => 'Gentium Book Basic',
						'Geo' => 'Geo',
						'Geostar' => 'Geostar',
						'Geostar Fill' => 'Geostar Fill',
						'Germania One' => 'Germania One',
						'Gilda Display' => 'Gilda Display',
						'Give You Glory' => 'Give You Glory',
						'Glass Antiqua' => 'Glass Antiqua',
						'Glegoo' => 'Glegoo',
						'Gloria Hallelujah' => 'Gloria Hallelujah',
						'Goblin One' => 'Goblin One',
						'Gochi Hand' => 'Gochi Hand',
						'Gorditas' => 'Gorditas',
						'Goudy Bookletter 1911' => 'Goudy Bookletter 1911',
						'Graduate' => 'Graduate',
						'Gravitas One' => 'Gravitas One',
						'Great Vibes' => 'Great Vibes',
						'Gruppo' => 'Gruppo',
						'Gudea' => 'Gudea',
						'Habibi' => 'Habibi',
						'Hammersmith One' => 'Hammersmith One',
						'Handlee' => 'Handlee',
						'Hanuman' => 'Hanuman',
						'Happy Monkey' => 'Happy Monkey',
						'Henny Penny' => 'Henny Penny',
						'Herr Von Muellerhoff' => 'Herr Von Muellerhoff',
						'Holtwood One SC' => 'Holtwood One SC',
						'Homemade Apple' => 'Homemade Apple',
						'Homenaje' => 'Homenaje',
						'IM Fell DW Pica' => 'IM Fell DW Pica',
						'IM Fell DW Pica SC' => 'IM Fell DW Pica SC',
						'IM Fell Double Pica' => 'IM Fell Double Pica',
						'IM Fell Double Pica SC' => 'IM Fell Double Pica SC',
						'IM Fell English' => 'IM Fell English',
						'IM Fell English SC' => 'IM Fell English SC',
						'IM Fell French Canon' => 'IM Fell French Canon',
						'IM Fell French Canon SC' => 'IM Fell French Canon SC',
						'IM Fell Great Primer' => 'IM Fell Great Primer',
						'IM Fell Great Primer SC' => 'IM Fell Great Primer SC',
						'Iceberg' => 'Iceberg',
						'Iceland' => 'Iceland',
						'Imprima' => 'Imprima',
						'Inconsolata' => 'Inconsolata',
						'Inder' => 'Inder',
						'Indie Flower' => 'Indie Flower',
						'Inika' => 'Inika',
						'Irish Grover' => 'Irish Grover',
						'Istok Web' => 'Istok Web',
						'Italiana' => 'Italiana',
						'Italianno' => 'Italianno',
						'Jim Nightshade' => 'Jim Nightshade',
						'Jockey One' => 'Jockey One',
						'Jolly Lodger' => 'Jolly Lodger',
						'Josefin Sans' => 'Josefin Sans',
						'Josefin Slab' => 'Josefin Slab',
						'Judson' => 'Judson',
						'Julee' => 'Julee',
						'Junge' => 'Junge',
						'Jura' => 'Jura',
						'Just Another Hand' => 'Just Another Hand',
						'Just Me Again Down Here' => 'Just Me Again Down Here',
						'Kameron' => 'Kameron',
						'Karla' => 'Karla',
						'Kaushan Script' => 'Kaushan Script',
						'Kelly Slab' => 'Kelly Slab',
						'Kenia' => 'Kenia',
						'Khmer' => 'Khmer',
						'Knewave' => 'Knewave',
						'Kotta One' => 'Kotta One',
						'Koulen' => 'Koulen',
						'Kranky' => 'Kranky',
						'Kreon' => 'Kreon',
						'Kristi' => 'Kristi',
						'Krona One' => 'Krona One',
						'La Belle Aurore' => 'La Belle Aurore',
						'Lancelot' => 'Lancelot',
						'Lato' => 'Lato',
						'League Script' => 'League Script',
						'Leckerli One' => 'Leckerli One',
						'Ledger' => 'Ledger',
						'Lekton' => 'Lekton',
						'Lemon' => 'Lemon',
						'Libre Baskerville' => 'Libre Baskerville',
						'Lilita One' => 'Lilita One',
						'Limelight' => 'Limelight',
						'Linden Hill' => 'Linden Hill',
						'Lobster' => 'Lobster',
						'Lobster Two' => 'Lobster Two',
						'Londrina Outline' => 'Londrina Outline',
						'Londrina Shadow' => 'Londrina Shadow',
						'Londrina Sketch' => 'Londrina Sketch',
						'Londrina Solid' => 'Londrina Solid',
						'Lora' => 'Lora',
						'Love Ya Like A Sister' => 'Love Ya Like A Sister',
						'Loved by the King' => 'Loved by the King',
						'Lovers Quarrel' => 'Lovers Quarrel',
						'Luckiest Guy' => 'Luckiest Guy',
						'Lusitana' => 'Lusitana',
						'Lustria' => 'Lustria',
						'Macondo' => 'Macondo',
						'Macondo Swash Caps' => 'Macondo Swash Caps',
						'Magra' => 'Magra',
						'Maiden Orange' => 'Maiden Orange',
						'Mako' => 'Mako',
						'Marcellus' => 'Marcellus',
						'Marcellus SC' => 'Marcellus SC',
						'Marck Script' => 'Marck Script',
						'Marko One' => 'Marko One',
						'Marmelad' => 'Marmelad',
						'Marvel' => 'Marvel',
						'Mate' => 'Mate',
						'Mate SC' => 'Mate SC',
						'Maven Pro' => 'Maven Pro',
						'Meddon' => 'Meddon',
						'MedievalSharp' => 'MedievalSharp',
						'Medula One' => 'Medula One',
						'Megrim' => 'Megrim',
						'Merienda One' => 'Merienda One',
						'Merriweather' => 'Merriweather',
						'Metal' => 'Metal',
						'Metamorphous' => 'Metamorphous',
						'Metrophobic' => 'Metrophobic',
						'Michroma' => 'Michroma',
						'Miltonian' => 'Miltonian',
						'Miltonian Tattoo' => 'Miltonian Tattoo',
						'Miniver' => 'Miniver',
						'Miss Fajardose' => 'Miss Fajardose',
						'Modern Antiqua' => 'Modern Antiqua',
						'Molengo' => 'Molengo',
						'Monofett' => 'Monofett',
						'Monoton' => 'Monoton',
						'Monsieur La Doulaise' => 'Monsieur La Doulaise',
						'Montaga' => 'Montaga',
						'Montez' => 'Montez',
						'Montserrat' => 'Montserrat',
						'Montserrat Alternates' => 'Montserrat Alternates',
						'Montserrat Subrayada' => 'Montserrat Subrayada',
						'Moul' => 'Moul',
						'Moulpali' => 'Moulpali',
						'Mountains of Christmas' => 'Mountains of Christmas',
						'Mr Bedfort' => 'Mr Bedfort',
						'Mr Dafoe' => 'Mr Dafoe',
						'Mr De Haviland' => 'Mr De Haviland',
						'Mrs Saint Delafield' => 'Mrs Saint Delafield',
						'Mrs Sheppards' => 'Mrs Sheppards',
						'Muli' => 'Muli',
						'Mystery Quest' => 'Mystery Quest',
						'Neucha' => 'Neucha',
						'Neuton' => 'Neuton',
						'News Cycle' => 'News Cycle',
						'Niconne' => 'Niconne',
						'Nixie One' => 'Nixie One',
						'Nobile' => 'Nobile',
						'Nokora' => 'Nokora',
						'Norican' => 'Norican',
						'Nosifer' => 'Nosifer',
						'Nothing You Could Do' => 'Nothing You Could Do',
						'Noticia Text' => 'Noticia Text',
						'Noto Sans' => 'Noto Sans',
						'Nova Cut' => 'Nova Cut',
						'Nova Flat' => 'Nova Flat',
						'Nova Mono' => 'Nova Mono',
						'Nova Oval' => 'Nova Oval',
						'Nova Round' => 'Nova Round',
						'Nova Script' => 'Nova Script',
						'Nova Slim' => 'Nova Slim',
						'Nova Square' => 'Nova Square',
						'Numans' => 'Numans',
						'Nunito' => 'Nunito',
						'Odor Mean Chey' => 'Odor Mean Chey',
						'Old Standard TT' => 'Old Standard TT',
						'Oldenburg' => 'Oldenburg',
						'Oleo Script' => 'Oleo Script',
						'Open Sans' => 'Open Sans',
						'Open Sans Condensed' => 'Open Sans Condensed',
						'Orbitron' => 'Orbitron',
						'Original Surfer' => 'Original Surfer',
						'Oswald' => 'Oswald',
						'Over the Rainbow' => 'Over the Rainbow',
						'Overlock' => 'Overlock',
						'Overlock SC' => 'Overlock SC',
						'Ovo' => 'Ovo',
						'Oxygen' => 'Oxygen',
						'PT Mono' => 'PT Mono',
						'PT Sans' => 'PT Sans',
						'PT Sans Caption' => 'PT Sans Caption',
						'PT Sans Narrow' => 'PT Sans Narrow',
						'PT Serif' => 'PT Serif',
						'PT Serif Caption' => 'PT Serif Caption',
						'Pacifico' => 'Pacifico',
						'Parisienne' => 'Parisienne',
						'Passero One' => 'Passero One',
						'Passion One' => 'Passion One',
						'Patrick Hand' => 'Patrick Hand',
						'Patua One' => 'Patua One',
						'Paytone One' => 'Paytone One',
						'Permanent Marker' => 'Permanent Marker',
						'Petrona' => 'Petrona',
						'Philosopher' => 'Philosopher',
						'Piedra' => 'Piedra',
						'Pinyon Script' => 'Pinyon Script',
						'Plaster' => 'Plaster',
						'Play' => 'Play',
						'Playball' => 'Playball',
						'Playfair Display' => 'Playfair Display',
						'Podkova' => 'Podkova',
						'Poiret One' => 'Poiret One',
						'Poller One' => 'Poller One',
						'Poly' => 'Poly',
						'Pompiere' => 'Pompiere',
						'Pontano Sans' => 'Pontano Sans',
						'Port Lligat Sans' => 'Port Lligat Sans',
						'Port Lligat Slab' => 'Port Lligat Slab',
						'Prata' => 'Prata',
						'Preahvihear' => 'Preahvihear',
						'Press Start 2P' => 'Press Start 2P',
						'Princess Sofia' => 'Princess Sofia',
						'Prociono' => 'Prociono',
						'Prosto One' => 'Prosto One',
						'Puritan' => 'Puritan',
						'Quantico' => 'Quantico',
						'Quattrocento' => 'Quattrocento',
						'Quattrocento Sans' => 'Quattrocento Sans',
						'Questrial' => 'Questrial',
						'Quicksand' => 'Quicksand',
						'Qwigley' => 'Qwigley',
						'Radley' => 'Radley',
						'Raleway' => 'Raleway',
						'Rammetto One' => 'Rammetto One',
						'Rancho' => 'Rancho',
						'Rationale' => 'Rationale',
						'Redressed' => 'Redressed',
						'Reenie Beanie' => 'Reenie Beanie',
						'Revalia' => 'Revalia',
						'Ribeye' => 'Ribeye',
						'Ribeye Marrow' => 'Ribeye Marrow',
						'Righteous' => 'Righteous',
						'Roboto' => 'Roboto',
						'Roboto Sans' => 'Roboto Sans',
						'Rochester' => 'Rochester',
						'Rock Salt' => 'Rock Salt',
						'Rokkitt' => 'Rokkitt',
						'Ropa Sans' => 'Ropa Sans',
						'Rosario' => 'Rosario',
						'Rosarivo' => 'Rosarivo',
						'Rouge Script' => 'Rouge Script',
						'Ruda' => 'Ruda',
						'Ruge Boogie' => 'Ruge Boogie',
						'Ruluko' => 'Ruluko',
						'Rum Raisin' => 'Rum Raisin',
						'Ruslan Display' => 'Ruslan Display',
						'Russo One' => 'Russo One',
						'Ruthie' => 'Ruthie',
						'Sacramento' => 'Sacramento',
						'Sail' => 'Sail',
						'Salsa' => 'Salsa',
						'Sancreek' => 'Sancreek',
						'Sansita One' => 'Sansita One',
						'Sarina' => 'Sarina',
						'Satisfy' => 'Satisfy',
						'Schoolbell' => 'Schoolbell',
						'Seaweed Script' => 'Seaweed Script',
						'Sevillana' => 'Sevillana',
						'Seymour One' => 'Seymour One',
						'Shadows Into Light' => 'Shadows Into Light',
						'Shadows Into Light Two' => 'Shadows Into Light Two',
						'Shanti' => 'Shanti',
						'Share' => 'Share',
						'Shojumaru' => 'Shojumaru',
						'Short Stack' => 'Short Stack',
						'Siemreap' => 'Siemreap',
						'Sigmar One' => 'Sigmar One',
						'Signika' => 'Signika',
						'Signika Negative' => 'Signika Negative',
						'Simonetta' => 'Simonetta',
						'Sirin Stencil' => 'Sirin Stencil',
						'Six Caps' => 'Six Caps',
						'Slackey' => 'Slackey',
						'Smokum' => 'Smokum',
						'Smythe' => 'Smythe',
						'Sniglet' => 'Sniglet',
						'Snippet' => 'Snippet',
						'Sofia' => 'Sofia',
						'Sonsie One' => 'Sonsie One',
						'Sorts Mill Goudy' => 'Sorts Mill Goudy',
						'Special Elite' => 'Special Elite',
						'Spicy Rice' => 'Spicy Rice',
						'Spinnaker' => 'Spinnaker',
						'Spirax' => 'Spirax',
						'Squada One' => 'Squada One',
						'Stardos Stencil' => 'Stardos Stencil',
						'Stint Ultra Condensed' => 'Stint Ultra Condensed',
						'Stint Ultra Expanded' => 'Stint Ultra Expanded',
						'Stoke' => 'Stoke',
						'Sue Ellen Francisco' => 'Sue Ellen Francisco',
						'Sunshiney' => 'Sunshiney',
						'Supermercado One' => 'Supermercado One',
						'Suwannaphum' => 'Suwannaphum',
						'Swanky and Moo Moo' => 'Swanky and Moo Moo',
						'Syncopate' => 'Syncopate',
						'Tangerine' => 'Tangerine',
						'Taprom' => 'Taprom',
						'Telex' => 'Telex',
						'Tenor Sans' => 'Tenor Sans',
						'The Girl Next Door' => 'The Girl Next Door',
						'Tienne' => 'Tienne',
						'Tinos' => 'Tinos',
						'Titan One' => 'Titan One',
						'Titillium Web' => 'Titillium Web',
						'Trade Winds' => 'Trade Winds',
						'Trocchi' => 'Trocchi',
						'Trochut' => 'Trochut',
						'Trykker' => 'Trykker',
						'Tulpen One' => 'Tulpen One',
						'Ubuntu' => 'Ubuntu',
						'Ubuntu Condensed' => 'Ubuntu Condensed',
						'Ubuntu Mono' => 'Ubuntu Mono',
						'Ultra' => 'Ultra',
						'Uncial Antiqua' => 'Uncial Antiqua',
						'UnifrakturCook' => 'UnifrakturCook',
						'UnifrakturMaguntia' => 'UnifrakturMaguntia',
						'Unkempt' => 'Unkempt',
						'Unlock' => 'Unlock',
						'Unna' => 'Unna',
						'VT323' => 'VT323',
						'Varela' => 'Varela',
						'Varela Round' => 'Varela Round',
						'Vast Shadow' => 'Vast Shadow',
						'Vibur' => 'Vibur',
						'Vidaloka' => 'Vidaloka',
						'Viga' => 'Viga',
						'Voces' => 'Voces',
						'Volkhov' => 'Volkhov',
						'Vollkorn' => 'Vollkorn',
						'Voltaire' => 'Voltaire',
						'Waiting for the Sunrise' => 'Waiting for the Sunrise',
						'Wallpoet' => 'Wallpoet',
						'Walter Turncoat' => 'Walter Turncoat',
						'Wellfleet' => 'Wellfleet',
						'Wire One' => 'Wire One',
						'Yanone Kaffeesatz' => 'Yanone Kaffeesatz',
						'Yellowtail' => 'Yellowtail',
						'Yeseva One' => 'Yeseva One',
						'Yesteryear' => 'Yesteryear',
						'Zeyada' => 'Zeyada'
		);



		// Image Alignment radio box
		$of_options_thumb_align = array("alignleft" => "Left","alignright" => "Right","aligncenter" => "Center"); 
		
		// Image Links to Options
		$of_options_image_link_to = array("image" => "The Image","post" => "The Post"); 


/*-----------------------------------------------------------------------------------*/
/* The Options Array */
/*-----------------------------------------------------------------------------------*/

// Set the Options Array
global $of_options;
$of_options = array();


// GENERAL //

$of_options[] = array( 	"name" 		=> "General",
						"type" 		=> "heading"
);

$of_options[] = array( "name" =>"Import Demo Content",
						"desc" =>"Click for import. Please ensure our plugins are activated before content is imported.",
						"id" => "demo_data",
						"std" => "",
						"btntext" => "Import Demo Content",
						"type" => "button",
						"options" => array(
										'classic' => "Classic Demo",
										'agency' => "Agency Demo",
						)
);



$of_options[] = array( 	"name" 		=> "Site Layout",
						"desc" 		=> "Selects site layout.",
						"id" 		=> "site_layout",
						"std" 		=> "",
						"type" 		=> "select",

						"options" 	=> array(
										"wide" => "Wide",
										"boxed" => "Boxed",
						)
);

$of_options[] = array( 	"name" 		=> "Site Background Color",
						"desc" 		=> "",
						"id" 		=> "site_bg_color",
						"std" 		=> "#eee",
						"type" 		=> "color"
);


$of_options[] = array( 	"name" 		=> "Site Background Image",
						"desc" 		=> "",
						"id" 		=> "site_bg_image",
						"std" 		=> get_template_directory_uri()."/images/bkgd1.jpg",
						"type" 		=> "media"
);

$of_options[] = array( 	"name" 		=> "Show Demo Panel",
						"desc" 		=> "Show demo panel in homepage for quick select color that you want",
						"id" 		=> "demo_show",
						"std" 		=> 0,
						"type" 		=> "checkbox"
);


//LOGO AND ICONS

$of_options[] = array( 	"name" 		=> "Logo and icons",
						"type" 		=> "heading"
);

$of_options[] = array( 	"name" 		=> "Logo",
						"desc" 		=> "Upload logo here.",
						"id" 		=> "site_logo",
						"std" 		=> get_template_directory_uri()."/images/logo.png",
						"type" 		=> "media"
);

$of_options[] = array( 	"name" 		=> "Favicon",
						"desc" 		=> "Add your custom Favicon image. 16x16px .ico or .png file required.",
						"id" 		=> "site_favicon",
						"std" 		=> "",
						"type" 		=> "media"
);

$of_options[] = array( 	"name" 		=> "Share icons",
						"desc" 		=> "Select icons to be shown on share icons on product page, blog and [share] shortcode",
						"id" 		=> "social_icons",
						"std" 		=> array("facebook","twitter","email","pinterest","googleplus"),
						"type" 		=> "multicheck",
						"options" 	=> array("facebook" => "Facebook","twitter" => "Twitter","email" => "Email","pinterest" => "Pinterest","googleplus" => "Google Plus")
);



// HEADER AND FOOTER
$footers_type = '';
$footers_type = get_posts(array('posts_per_page'=>-1, 'post_type'=>'footer'));
$footers_option = array();
foreach ($footers_type as $key => $value){
	$footers_option[$value->ID] = $value->post_title;
}
$of_options[] = array( 	"name" 		=> "Header and Footer",
						"type" 		=> "heading"
);

$url =  ADMIN_DIR . 'assets/images/';

$of_options[] = array( 	"name" 		=> "Header type",
						"desc" 		=> "Select header type",
						"id" 		=> "header-type",
						"std" 		=> "1",
						"type" 		=> "images",
						"options" 	=> array(
											'1' 	=> $url . 'header-1.gif',
											'2' 	=> $url . 'header-2.gif',
											'3' 	=> $url . 'header-3.gif',
						)
);

$of_options[] = array( 	"name" 					=> "Footer type",
						"desc" 					=> "Select footer type",
						"id" 					=> "footer-type",
						"type" 					=> "select",
						'override_numberic'		=> true,
						"options" 				=> $footers_option
);

$of_options[] = array( 	"name" 		=> "Fixed navigation",
						"desc" 		=> "",
						"id" 		=> "fixed_nav",
						"desc"      => "Fixed navigation at top position",
						"std" 		=> 1,
						"type" 		=> "checkbox"
);

$of_options[] = array( 	"name" 		=> "Show Top Bar",
						"desc" 		=> "Show top bar",
						"id" 		=> "topbar_show",
						"std" 		=> 1,
						"type" 		=> "checkbox"
);

$of_options[] = array( 	"name" 		=> "Enable Switch Languages",
						"desc" 		=> "Enable Switch Languages",
						"id" 		=> "switch_lang",
						"std" 		=> 1,
						"type" 		=> "checkbox"
);

$of_options[] = array( 	"name" 	=> "Top bar left",
				"desc" 		=> "Insert text for left top bar.",
				"id" 		=> "topbar_left",
				"std" 		=> "Add something here...",
				"type" 		=> "text"
);



// TYPE 

$of_options[] = array( 	"name" 		=> "Fonts",
						"type" 		=> "heading"
);


$of_options[] = array( 	"name" 		=> "Heading fonts (H1, H2)",
						"desc" 		=> "Select heading fonts.",
						"id" 		=> "type_headings",
						"std" 		=> "Montserrat",
						"type" 		=> "select_google_font",
						"preview" 	=> array(
										"text" => "<strong>LeeTheme</strong> <br><span style='font-size:60%!important'>UPPERCASE TEXT</span>", 
										"size" => "30px" 
						),
						"options" 	=>  $google_fonts
);


$of_options[] = array( 	"name" 		=> "Text fonts (paragraphs, buttons, sub-navigations)",
						"desc" 		=> "Select heading fonts",
						"id" 		=> "type_texts",
						"std" 		=> "Raleway",
						"type" 		=> "select_google_font",
						"preview" 	=> array(
										"text" => "Lorem ipsum dosectetur adipisicing elit, sed do.Lorem ipsum dolor sit amet, consectetur Nulla fringilla purus at leo dignissim congue. Mauris elementum accumsan leo vel tempor. Sit amet cursus nisl aliquam. Aliquam et elit eu nunc rhoncus viverra quis at felis..", //this is the text from preview box
										"size" => "14px" 
						),
						"options" 	=>  $google_fonts
);

$of_options[] = array( 	"name" 		=> "Main navigation",
						"desc" 		=> "Select heading fonts",
						"id" 		=> "type_nav",
						"std" 		=> "Montserrat",
						"type" 		=> "select_google_font",
						"preview" 	=> array(
										"text" => "<span style='font-size:45%'>THIS IS THE TEXT.</span>", 
										"size" => "30px" 
						),
						"options" 	=>  $google_fonts
);


$of_options[] = array( 	"name" 		=> "Alterntative font (.alt-font)",
						"desc" 		=> "Select alternative font",
						"id" 		=> "type_alt",
						"std" 		=> "Dancing Script",
						"type" 		=> "select_google_font",
						"preview" 	=> array(
										"text" => "This is the text.",
										"size" => "30px" 
						),
						"options" 	=>  $google_fonts
);


$of_options[] = array( 	"name" 		=> "Character Sub-sets",
						"desc" 		=> "Choose the character sets you want.",
						"id" 		=> "type_subset",
						"std" 		=> array("latin"),
						"type" 		=> "multicheck",
						"options" 	=> array("latin" => "Latin","cyrillic-ext" => "Cyrillic Extended","greek-ext" => "Greek Extended","greek" => "Greek","vietnamese" => "Vietnamese","latin-ext" => "Latin Extended","cyrillic" => "Cyrillic")
);


// COLORS


$of_options[] = array( 	"name" 		=> "Style and Colors",
						"type" 		=> "heading",
);

$of_options[] = array( 	"name" 		=> "Primary Color",
						"desc" 		=> "Change primary color. Used for primary buttons, link hover, background, etc.",
						"id" 		=> "color_primary",
						"std" 		=> "#ff5555",
						"type" 		=> "color"
);

$of_options[] = array( 	"name" 		=> "Secondary Color",
						"desc" 		=> "Change secondary color. Used for sale bubble.",
						"id" 		=> "color_secondary",
						"std" 		=> "#ff7171",
						"type" 		=> "color"
);

$of_options[] = array( 	"name" 		=> "Success Color",
						"desc" 		=> "Change the success color. Used for global success messages.",
						"id" 		=> "color_success",
						"std" 		=> "#5cb85c",
						"type" 		=> "color"
);

$of_options[] = array( 	"name" 		=> "Buttons Color",
						"desc" 		=> "Change color for buttons.",
						"id" 		=> "color_button",
						"std" 		=> "#fff",
						"type" 		=> "color"
);

$of_options[] = array( 	"name" 		=> "Buttons Color Hover Effect",
						"desc" 		=> "Change color hover for buttons. Default is primary color",
						"id" 		=> "color_hover",
						"std" 		=> "#ff5555",
						"type" 		=> "color"
);

$of_options[] = array( 	"name" 		=> "Product Page",
						"type" 		=> "heading",
);


$of_options[] = array( 	"name" 		=> "Product Sidebar",
						"desc" 		=> "",
						"id" 		=> "product_sidebar",
						"std" 		=> "left_sidebar",
						"type" 		=> "select",
						"options" 	=> array(
										"no_sidebar" => "No sidebar",
										"left_sidebar" => "Left Sidebar",
										"right_sidebar" => "Right Sidebar"
						)
);


$of_options[] = array( 	"name"  => "Additional Global tab/section title",
				"id" 		=> "tab_title",
				"std" 		=> "Add Tags",
				"type" 		=> "text"
);

$of_options[] = array( 	"name" 		=> "Additional Global tab/section content",
				"id" 		=> "tab_content",
				"std" 		=> "Custom Tab Content here. <br>
								Tail sed sausage magna quis commodo swine. Aliquip strip steak esse ex in ham hock fugiat in. Labore velit pork belly eiusmod ut shank doner capicola consectetur landjaeger fugiat excepteur short loin. Pork belly laboris mollit in leberkas qui. 
								Pariatur swine aliqua pork chop venison veniam. Venison sed cow short loin bresaola shoulder cupidatat capicola drumstick dolore magna shankle.",
				"type" 		=> "textarea",
				"desc"      => ""
);

$of_options[] = array( 	"name" 		=> "Category sidebar",
						"desc" 		=> "Select if you want a sidebar on product categories.",
						"id" 		=> "category_sidebar",
						"std" 		=> "left-sidebar",
						"type" 		=> "select",

						"options" 	=> array(
										"left-sidebar" => "Left sidebar",
										"right-sidebar" => "Right sidebar",
										"no-sidebar" => "No sidebar",

						)
);

$of_options[] = array( 	"name" 		=> "Products per row",
						"desc" 		=> "Change products number display per row for the Shop page",
						"id" 		=> "products_per_row",
						"std" 		=> "3",
						"type" 		=> "select",
						"options"   => array("2" => "2", "3" => "3", "4" => "4", "5" => "5" )
);

$of_options[] = array( 
				"name"  => "Products per page",
				"id" 		=> "products_pr_page",
				"desc" => "Change products per page.",
				"std" 		=> "12",
				"type" 		=> "text"
);


$of_options[] = array( 	"name" 		=> "Hover product effect",
						"desc" 		=> "Select if you want change hover product image.",
						"id" 		=> "animated_products",
						"std" 		=> "hover-flip",
						"type" 		=> "select",

						"options" 	=> array(
										"hover-flip" => "Flip Horizontal",
										"" => "No effect",
						)
);

$of_options[] = array( 	"name" 		=> "Enable Hover product Zoom",
						"desc" 		=> "",
						"id" 		=> "product-zoom",
						"desc"      => "Enable product hover zoom on product detail page",
						"std" 		=> 1,
						"type" 		=> "checkbox"
);


$of_options[] = array( 
				"name"  => "Category shop banner",
				"id" 		=> "cat_bg",
				"desc" => "Change category shop banner with short code .",
				"std" 		=> "[rev_slider shop_banner]",
				"type" 		=> "text"
);

$url =  ADMIN_DIR . 'assets/images/';


$of_options[] = array( 	"name" 		=> "Blog",
						"type" 		=> "heading"
);


$of_options[] = array( 	"name" 		=> "Blog layout",
						"desc" 		=> "Change blog layout",
						"id" 		=> "blog_layout",
						"std" 		=> "left-sidebar",
						"type" 		=> "select",
						"options"   => array("left-sidebar" => "Left sidebar", "right-sidebar" => "Right sidebar", "no-sidebar" => "No sidebar (Centered)" )
);


$of_options[] = array( 	"name" 		=> "Blog style",
						"desc" 		=> "Change blog style",
						"id" 		=> "blog_type",
						"std" 		=> "blog-standard",
						"type" 		=> "select",
						"options"   => array("blog-standard" => "Standard", "blog-list" => "List style")
);



$of_options[] = array( 	"name" 		=> "Parallax effect",
						"desc" 		=> "",
						"id" 		=> "blog_parallax",
						"desc"      => "Enable parallax effect on featured images",
						"std" 		=> 1,
						"type" 		=> "checkbox"
);


$of_options[] = array( 	"name" 		=> "Promo Popup",
						"type" 		=> "heading"
);

$of_options[] = array( 	"name" 		=> "Enable promo popup",
						"desc" 		=> "Enable promo popup",
						"id" 		=> "promo_popup",
						"std" 		=> 1,
						"type" 		=> "checkbox"
);

$of_options[] = array( 
				"name"  => "Popup width",
				"id" 		=> "pp_width",
				"desc" => "",
				"std" 		=> "780",
				"type" 		=> "text"
);

$of_options[] = array( 
				"name"  => "Popup height",
				"id" 		=> "pp_height",
				"desc" => "",
				"std" 		=> "360",
				"type" 		=> "text"
);

$of_options[] = array( 	"name" 		=> "Popup content",
				"id" 		=> "pp_content",
				"std" 		=> "<h2>NEWSLETTER</h2>
								<span>Subscribe now to get <span class='primary-color'>20</span>% off on any product!</span>
								[ninja_forms id=9]",
				"type" 		=> "textarea",
				"desc"      => ""
);


$of_options[] = array( 	"name" 		=> "Popup Background",
						"desc" 		=> "Insert popup background.",
						"id" 		=> "pp_background",
						"std" 		=> get_template_directory_uri().'/images/promo_bg.jpg',
						"type" 		=> "media"
);

				
// Backup Options
$of_options[] = array( 	"name" 		=> "Backup and Import",
						"type" 		=> "heading",
				);
				
$of_options[] = array( 	"name" 		=> "Backup and Restore Options",
						"id" 		=> "of_backup",
						"std" 		=> "",
						"type" 		=> "backup",
						"desc" 		=> 'You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.',
				);
				
$of_options[] = array( 	"name" 		=> "Transfer Theme Options Data",
						"id" 		=> "of_transfer",
						"std" 		=> "",
						"type" 		=> "transfer",
						"desc" 		=> 'You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options".',
);


				
}//End function: of_options()





}//End chack if function exists: of_options()
?>

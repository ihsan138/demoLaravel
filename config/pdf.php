<?php

return [
	'mode'                 => '',
	'format'               => 'A4',
	'default_font_size'    => '11',
	'default_font'         => 'FreeSerif',//DejaVuSerif,sans-serif  // vendor\mpdf\mpdf\ttfonts //glyphIDtoUni error maybe due to not using sans-serif as default fonts
	'margin_left'          => 10,
	'margin_right'         => 10,
	'margin_top'           => 10,
	'margin_bottom'        => 10,
	'margin_header'        => 0,
	'margin_footer'        => 0,
	'orientation'          => 'P',
	'title'                => 'Laravel mPDF',
	'author'               => '',
	'watermark'            => '',
	'show_watermark'       => false,
	'watermark_font'       => 'sans-serif',
	'display_mode'         => 'fullpage',
	'watermark_text_alpha' => 0.1,
	'custom_font_dir' => base_path('resources/fonts/'), // don't forget the trailing slash!
	'custom_font_data' => [
		//dont put fontawesome, put fawesome instead (fontawesome will trigger a bug)
        'fawesome' => [
            'R' => 'fa-regular-400.ttf'
        ],
		'sapura' => [
			'R'  => 'Sapur.ttf',    // regular font
			'B'  => 'SapurBol.ttf',       // optional: bold font
			'I'  => 'SapurIta.ttf',     // optional: italic font
			// 'BI' => 'SapurLig.ttf' // optional: bold-italic font
		]
		// 'examplefont' => [
		// 	'R'  => 'ExampleFont-Regular.ttf',    // regular font
		// 	'B'  => 'ExampleFont-Bold.ttf',       // optional: bold font
		// 	'I'  => 'ExampleFont-Italic.ttf',     // optional: italic font
		// 	'BI' => 'ExampleFont-Bold-Italic.ttf' // optional: bold-italic font
		// ]
		// ...add as many as you want.
	],
	'auto_language_detection'  => false,
	'temp_dir'               => rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR),
	'pdfa' 			=> false,
        'pdfaauto' 		=> false,
];
<?php

return array(
	'region' => array(
		'type' => 'string',
		'description' => '',
		'sanitize_callback' => 'sanitize_text_field',
		'show_in_rest' => false,
		'default' => '',
		'title' => __('Region', 'jcifi'),
		'field' => array(
			'type' => 'select',
			'selected' => '',
			'placeholder' => __('Select a region', 'jcifi'),
			'options' => array(
				'SNKK' => __('JCI Finland', 'jcifi'),
				'Alue A' => __('Region A', 'jcifi'),
				'Alue B' => __('Region B', 'jcifi'),
				'Alue C' => __('Region C', 'jcifi'),
				'Alue D' => __('Region D', 'jcifi'),
			),
		),
	),
	'unit' => array(
		'type' => 'string',
		'description' => '',
		'sanitize_callback' => 'sanitize_text_field',
		'show_in_rest' => false,
		'default' => '',
		'title' => __('Unit', 'jcifi'),
		'field' => array(
			'type' => 'select',
			'selected' => '',
			'placeholder' => __('Select a local organization', 'jcifi'),
			'options' => array(
				'Airiston Nkk' => 'JCI Airisto',
				'Akaan Nkk' => 'JCI Akaa' ,
				'Aurajoen Nkk' => 'JCI Aurajoki',
				'Auranmaan Nkk' => 'JCI Auranmaa',
				'JCI Cosmopolis' => 'JCI Cosmopolis',
				'Espoon Nkk' => 'JCI Espoo',
				'Forssan Seudun Nkk' => 'JCI Forssa',
				'Haminan Nkk' => 'JCI Hamina',
				'Hangon Nkk' => 'JCI Hanko',
				'Havis Amandan Nkk' => 'JCI Havis Amanda',
				'Heinolan Nkk' => 'JCI Heinola',
				'Helsingin Nkk' => 'JCI Helsinki',
				'Hämeenlinnan Nkk' => 'JCI Hämeenlinna',
				'Imatran Nkk' => 'JCI Imatra',
				'Joensuun Nkk' => 'JCI Joensuu',
				'Jyväskylän Nkk' => 'JCI Jyväskylä',
				'Järvenpään Seudun Nkk' => 'JCI Järvenpää',
				'Kajaanin Nkk' => 'JCI Kajaani',
				'Kalajokilaakson Nkk' => 'JCI Kalajokilaakso',
				'Kankaanpään Nkk' => 'JCI Kankaanpää',
				'Keski-Uudenmaan Nkk' => 'JCI Keski-Uusimaa',
				'Keskuspuiston Nkk' => 'JCI Keskuspuisto',
				'Keurusselän Nkk' => 'JCI Keurusselkä',
				'Kiteen Nkk' => 'JCI Kitee',
				'Kokkolan Nkk' => 'JCI Kokkola',
				'Kotkan Nkk' => 'JCI Kotka',
				'Kouvolan Nkk' => 'JCI Kouvola',
				'Kuopion Nkk' => 'JCI Kuopio',
				'Kuusamon Nkk' => 'JCI Kuusamo',
				'Lahden Nkk' => 'JCI Lahti',
				'Lappeenrannan Nkk' => 'JCI Lappeenranta',
				'Liminganlahden Nkk' => 'JCI Liminganlahti',
				'Lohjan Nkk' => 'JCI Lohja',
				'Loimaan Seudun Nkk' => 'JCI Loimaa',
				'Luoteisen Keski-Suomen Nkk' => 'JCI Luoteis Keski-Suomi',
				'JCI Mariehamn' => 'JCI Mariehamn',
				'Meri-Lapin Nkk' => 'JCI Meri-Lappi',
				'Metropolin Nkk' => 'JCI Metropol',
				'Mikkelin Nkk' => 'JCI Mikkeli',
				'Muuramen Seudun Nkk' => 'JCI Muurame',
				'Mäntsälän Nkk' => 'JCI Mäntsälä',
				'Oulun Nkk' => 'JCI Oulu',
				'Oulun Tervaporvarien Nkk' => 'JCI Oulun Tervaporvarit',
				'Pieksämäen Nkk' => 'JCI Pieksämäki',
				'Pirkanmaan Nkk' => 'JCI Pirkanmaa',
				'Porin Nkk' => 'JCI Pori',
				'Porvoon Nkk' => 'JCI Porvoo',
				'Pyhäjärviseudun Nkk' => 'JCI Pyhäjärvenseutu',
				'Raahen Nkk' => 'JCI Raahe',
				'Raisio-Naantalin Nkk' => 'JCI Rasio-Naantali',
				'Rauman Nkk' => 'JCI Rauma',
				'Riihimäen-Hyvinkään Seudun Nkk' => 'JCI Riihimäki-Hyvinkää',
				'Rovaniemen Nkk' => 'JCI Rovaniemi',
				'Salon Nkk' => 'JCI Salo',
				'Savonlinnan Seudun Nkk' => 'JCI Savonlinna',
				'Seinäjoen Seudun Nkk' => 'JCI Seinäjoki',
				'Siilinjärven Nkk' => 'JCI Siilinjärvi',
				'Suupohjan Nkk' => 'JCI Suupohja',
				'Tammerkosken Nkk' => 'JCI Tammerkoski',
				'Tampereen Nkk' => 'JCI Tampere',
				'Turun Nkk' => 'JCI Turku',
				'JCI United' => 'JCI United',
				'Vaasan Nkk' => 'JCI Vaasa',
				'Valkeakosken Nkk' => 'JCI Valkeakoski',
				'Vammalan Nkk' => 'JCI Vammala',
				'Vihdin Nkk' => 'JCI Vihti',
				'Warkauden Nkk' => 'JCI Warkaus',
				'Ylä-Savon Nkk' => 'JCI Ylä-Savo',
			),
		),
	),
);

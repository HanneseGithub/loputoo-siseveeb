<?php

/*
 * Siseveeb user roles
 */

add_role('singer', 'Laulja');
add_role('bookie', 'Raamatupidaja');
add_role('conductor', 'Koorivanem');
add_role('president', 'President');
add_role('secretary', 'Sekretär');
add_role('note_handler', 'Noodihaldur');

$bookie  = get_role('bookie');
$singer = get_role('singer');
$conductor = get_role('conductor');
$choirManager = get_role('president');
$secretary = get_role('secretary');
$noteHandler = get_role('note_handler');

// Same capabilities as a subscriber
$bookie -> add_cap('read');
$singer -> add_cap('read');
$conductor -> add_cap('read');
$choirManager -> add_cap('read');
$secretary -> add_cap('read');
$noteHandler -> add_cap('read');
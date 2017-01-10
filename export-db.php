<?php
include 'db.php';
include 'importer.php';

$export = new Export;

$export->export_tables("localhost",DB_USER,DB_PASSWORD,DB_NAME);
//this will delete all exiting tables, and writes the imported database
//import_tables("localhost","root","","salesdb","salesdb___(08-23-59_21-04-2016)__rand3118219.sql", true);

//dont delete the exiting tables, just add those, which doesnt exist
//import_tables("localhost","root","","salesdb","salesdb___(08-23-59_21-04-2016)__rand3118219.sql", false);



?>

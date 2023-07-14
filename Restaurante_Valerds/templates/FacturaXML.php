<?php
$xml = new DomDocument('1.0', 'UTF-8'); 
$root = $xml->createElement('colaboradores'); 
$root = $xml->appendChild($root); 

$colaborador=$xml->createElement('colaborador'); 
$colaborador =$root->appendChild($colaborador); 


$nom=$xml->createElement('nombre','Erick'); 
$nom =$colaborador->appendChild($nom); 

$apellido=$xml->createElement('apellido','campos'); 
$apellido =$colaborador->appendChild($apellido); 
 
$nom=$xml->createElement('nombre','Robert'); 
$nom =$colaborador->appendChild($nom); 

$apellido=$xml->createElement('apellido','Lopez'); 
$apellido =$colaborador->appendChild($apellido); 


$xml->formatOutput = true; 
 $filePath = __DIR__ . '/../../../app/';
 $fileName = 'xmlLog_' . date('d.m.Y') . '.xml';
            $strings_xml = $xml->saveXML(); 
            $xml->save(); 

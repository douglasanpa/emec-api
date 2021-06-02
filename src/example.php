<?php
require "MecApi.php";
require "Service.php";
$mec = New \MecApi\MecApi;
//print_r($mec->getMunicipios('SP'));
print_r($mec->getInstituicoesSimples('SP','Guarulhos'));
//print_r($mec->getInstituicaoEnderecos('14727'));
//print_r($mec->getInstituicaoCursos('1046615','14727'));
//print_r(json_encode($mec->getTodasInstituicoesMunicipio('Guarulhos')));

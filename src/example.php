<?php
require("MecApi.php");
require("Service.php");
$mec = New \App\MecApi;
//print_r($mec->getMunicipios('SP'));
//print_r($mec->getInstituicoes('SP','000000003518800'));
//print_r($mec->getInstituicaoEnderecos('14727'));
//print_r($mec->getInstituicaoCursos('1046615','14727'));
print_r(json_encode($mec->getTodasInstituicoesMunicipio('águas de Lindóia')));

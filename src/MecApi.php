<?php

namespace MecApi;

use MecApi\Service;

class MecApi
{
    public function getMunicipios($sigla)
    {
        return file_get_contents("http://emec.mec.gov.br/emec/comum/json/selecionar-municipio/"
            . md5("sg_uf") . "/" . base64_encode($sigla));
        
    }

    public function getInstituicoesSimples($estado, $municipio)
    {
        include_once('simple_html_dom.php');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://emec.mec.gov.br/emec/nova-index/listar-consulta-simples/list/1000');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING , "gzip");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "data%5BCONSULTA_SIMPLES%5D%5Bhid_template%5D=listar-consulta-simples-endereco&data%5BCONSULTA_SIMPLES%5D%5Bhid_order%5D=ies_endereco.no_campus+ASC&data%5BCONSULTA_SIMPLES%5D%5Bhid_no_cidade_simples%5D=&data%5BCONSULTA_SIMPLES%5D%5Bhid_no_regiao_simples%5D=&data%5BCONSULTA_SIMPLES%5D%5Bhid_no_pais_simples%5D=&data%5BCONSULTA_SIMPLES%5D%5Bhid_co_pais_simples%5D=&data%5BCONSULTA_SIMPLES%5D%5Bsel_tp_filtro%5D=ds_municipio&data%5BCONSULTA_SIMPLES%5D%5Btxt_ds_filtro%5D={$municipio}&data%5BCONSULTA_SIMPLES%5D%5Bsel_tp_filtro_indice%5D=&data%5BCONSULTA_SIMPLES%5D%5Bsel_tp_filtro_organizacao%5D=&data%5BCONSULTA_SIMPLES%5D%5Bsel_tp_filtro_cat_administrativa%5D=&data%5BCONSULTA_SIMPLES%5D%5Bsel_tp_filtro_natureza_juridica%5D=&data%5BCONSULTA_SIMPLES%5D%5Bsel_tp_filtro_st_gratuito%5D=&data%5BCONSULTA_SIMPLES%5D%5Bsel_co_area%5D=&captcha=");
        
        $buffer = curl_exec($ch);
        curl_close($ch);
        $dom = new \domDocument;

        @$dom->loadHTML($buffer);
        $dom->preserveWhiteSpace = false;
        $tables = $dom->getElementsByTagName('tr');

        $array['cod_uf'] = $estado;
        $array['cod_municipio'] = $municipio;
        $array['header'] = Service::mountHeaderInstitutions($tables,2);
        $array['body']  = Service::mountBodyInstitutions($tables, $array);

        return $array;
    }

    public function getInstituicoes($cod_uf, $cod_municipio)
    {
        include_once('simple_html_dom.php');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://emec.mec.gov.br/emec/nova-index/listar-consulta-avancada/list/1000');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING , "gzip");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "data%5BCONSULTA_AVANCADA%5D%5Bhid_template%5D=listar-consulta-avancada-ies&data%5BCONSULTA_AVANCADA%5D%5Bhid_order%5D=ies.no_ies+ASC&data%5BCONSULTA_AVANCADA%5D%5Bhid_no_cidade_avancada%5D=&data%5BCONSULTA_AVANCADA%5D%5Bhid_no_regiao_avancada%5D=&data%5BCONSULTA_AVANCADA%5D%5Bhid_no_pais_avancada%5D=&data%5BCONSULTA_AVANCADA%5D%5Bhid_co_pais_avancada%5D=&data%5BCONSULTA_AVANCADA%5D%5Brad_buscar_por%5D=IES&data%5BCONSULTA_AVANCADA%5D%5Btxt_no_ies%5D=&data%5BCONSULTA_AVANCADA%5D%5Btxt_no_curso%5D=&data%5BCONSULTA_AVANCADA%5D%5Btxt_no_especializacao%5D=&data%5BCONSULTA_AVANCADA%5D%5Bsel_co_area%5D=&data%5BCONSULTA_AVANCADA%5D%5Bsel_sg_uf%5D=MG&data%5BCONSULTA_AVANCADA%5D%5Bsel_co_municipio%5D=".$cod_municipio."&data%5BCONSULTA_AVANCADA%5D%5Bchk_tp_natureza_gn%5D%5B%5D=3&data%5BCONSULTA_AVANCADA%5D%5Bchk_tp_natureza_gn%5D%5B%5D=1&data%5BCONSULTA_AVANCADA%5D%5Bchk_tp_natureza_gn%5D%5B%5D=2&data%5BCONSULTA_AVANCADA%5D%5Bchk_tp_natureza_gn%5D%5B%5D=5&data%5BCONSULTA_AVANCADA%5D%5Bchk_tp_natureza_gn%5D%5B%5D=4&data%5BCONSULTA_AVANCADA%5D%5Bchk_tp_natureza_gn%5D%5B%5D=6&data%5BCONSULTA_AVANCADA%5D%5Bchk_tp_natureza_gn%5D%5B%5D=7&data%5BCONSULTA_AVANCADA%5D%5Bsel_st_gratuito%5D=&data%5BCONSULTA_AVANCADA%5D%5Bchk_tp_organizacao_gn%5D%5B%5D=10022%2C10024%2C10023%2C10027&data%5BCONSULTA_AVANCADA%5D%5Bchk_tp_organizacao_gn%5D%5B%5D=10019%2C10020%2C10021%2C10026&data%5BCONSULTA_AVANCADA%5D%5Bchk_tp_organizacao_gn%5D%5B%5D=10026%2C10019&data%5BCONSULTA_AVANCADA%5D%5Bchk_tp_organizacao_gn%5D%5B%5D=10028%2C10029&data%5BCONSULTA_AVANCADA%5D%5Bsel_no_indice_ies%5D=&data%5BCONSULTA_AVANCADA%5D%5Bsel_co_indice_ies%5D=&data%5BCONSULTA_AVANCADA%5D%5Bsel_no_indice_curso%5D=&data%5BCONSULTA_AVANCADA%5D%5Bsel_co_indice_curso%5D=&data%5BCONSULTA_AVANCADA%5D%5Bsel_co_situacao_funcionamento_ies%5D=10035&data%5BCONSULTA_AVANCADA%5D%5Bsel_co_situacao_funcionamento_curso%5D=9&data%5BCONSULTA_AVANCADA%5D%5Bsel_st_funcionamento_especializacao%5D=&captcha=");
        
        $buffer = curl_exec($ch);
        curl_close($ch);
        $dom = new \domDocument;

        @$dom->loadHTML($buffer);
        $dom->preserveWhiteSpace = false;
        $tables = $dom->getElementsByTagName('tr');

        $array['cod_uf'] = $cod_uf;
        $array['cod_municipio'] = $cod_municipio;
        $array['header'] = Service::mountHeaderInstitutions($tables,2);
        $array['body']  = Service::mountBodyInstitutions($tables, $array);

        return $array;
    }

    public function getTodasInstituicoesMunicipio($city)
    {
        $city = str_replace(" ","+",$city);
        include_once('simple_html_dom.php');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://emec.mec.gov.br/emec/nova-index/gerar-arquivo-relatorio-consultar-simples?data%5BCONSULTA_SIMPLES%5D%5Bhid_template%5D=listar-consulta-simples-endereco&data%5BCONSULTA_SIMPLES%5D%5Bhid_order%5D=ies_endereco.no_campus+ASC&data%5BCONSULTA_SIMPLES%5D%5Bhid_no_cidade_simples%5D=&data%5BCONSULTA_SIMPLES%5D%5Bhid_no_regiao_simples%5D=&data%5BCONSULTA_SIMPLES%5D%5Bhid_no_pais_simples%5D=&data%5BCONSULTA_SIMPLES%5D%5Bhid_co_pais_simples%5D=&data%5BCONSULTA_SIMPLES%5D%5Bsel_tp_filtro%5D=ds_municipio&data%5BCONSULTA_SIMPLES%5D%5Btxt_ds_filtro%5D={$city}&data%5BCONSULTA_SIMPLES%5D%5Bsel_tp_filtro_indice%5D=&data%5BCONSULTA_SIMPLES%5D%5Bsel_tp_filtro_organizacao%5D=&data%5BCONSULTA_SIMPLES%5D%5Bsel_tp_filtro_cat_administrativa%5D=&data%5BCONSULTA_SIMPLES%5D%5Bsel_tp_filtro_natureza_juridica%5D=&data%5BCONSULTA_SIMPLES%5D%5Bsel_tp_filtro_st_gratuito%5D=&data%5BCONSULTA_SIMPLES%5D%5Bsel_co_area%5D=&captcha=&data[CONSULTA_SIMPLES][hid_format_ext]=xls&data[CONSULTA_SIMPLES][hid_st_nome_consulta]=endereco");
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:25.0) Gecko/20100101 Firefox/25.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $buffer = curl_exec($ch);
        curl_close($ch);

        $dom = new \domDocument;

        @$dom->loadHTML($buffer);
        $dom->preserveWhiteSpace = false;
        $tables = $dom->getElementsByTagName('tr');
        $array['cidade'] = $city;
        $array['header'] = Service::mountHeaderInstitutions($tables,1);
        $array['body']  = Service::mountBodyInstitutions($tables, $array);

        return $array;
    }

    public function getInstituicaoEnderecos($codigoInstituicao)
    {
        $html = file_get_contents(
            'http://emec.mec.gov.br/emec/consulta-ies/listar-endereco/d96957f455f6405d14c6542552b0f6eb/' .
            base64_encode($codigoInstituicao) . '/list/1000'
        );

        include_once('simple_html_dom.php');

        $dom = new \domDocument;
        @$dom->loadHTML($html);
        $dom->preserveWhiteSpace = false;
        $tables = $dom->getElementsByTagName('tbody');

        foreach ($tables as $row) {
            $cols = $row->getElementsByTagName('td');
            $array[trim($cols->item(0)->nodeValue)] = array(
                'denominacao' => trim($cols->item(1)->nodeValue),
                'endereco' => trim($cols->item(2)->nodeValue),
                'polo' => trim($cols->item(3)->nodeValue),
                'municipio' => trim($cols->item(4)->nodeValue),
                'UF' => preg_replace("/[^A-Z{2}]/", "", $cols->item(5)->nodeValue)
            );
        }
        return $array;
    }

    public function getInstituicaoCursos($cod_endereco, $cod_instituicao)
    {
        $html = file_get_contents(
            'http://emec.mec.gov.br/emec/consulta-ies/listar-curso-endereco/d96957f455f6405d14c6542552b0f6eb/' .
            base64_encode($cod_instituicao) . '/aa547dc9e0377b562e2354d29f06085f/'
            . base64_encode($cod_endereco) . '/list/1000'
        );

        include_once('simple_html_dom.php');

        $dom = new \domDocument;
        @$dom->loadHTML($html);
        $dom->preserveWhiteSpace = false;
        $tables = $dom->getElementsByTagName('tbody');

        foreach ($tables as $row) {
            $cols = $row->getElementsByTagName('td');
            $array[] = preg_replace("/[^A-Za-z]/", "", $cols->item(0)->nodeValue);
        }
        return $array;
    }
}

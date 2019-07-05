<?php 

namespace App\Functions;

use DB; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Functions\IntrawayFunctions;

class MulticonsultaFunctions {
 
    function validarSearch($bus,$tipoBus,$fech_hor,$rol){
        //compruebo que el tamaño del string sea válido.
        
        $mensaje = "";
    
        if (preg_match("/^[a-zA-Z0-9\:.-]+$/", $bus) == 1) {
            
            if(strlen($bus)<1 || strlen($bus)> 20){
                $mensaje = 'El BUS enviado no es válido'; 
                $this->updateConsulta($bus,$mensaje,$fech_hor,$rol);
                throw ValidationException::withMessages([ "texto" => "El campo de búsqueda es invalido" ]);
            }
            if($tipoBus == "seleccionar" || ($tipoBus != 1 && $tipoBus != 2 && $tipoBus != 3 && $tipoBus != 4)){  
                $mensaje = 'El tipo de busqueda no es válido';  
                $this->updateConsulta($bus,$mensaje,$fech_hor,$rol);
                throw ValidationException::withMessages([ "tipo" => "Seleccione un tipo de busqueda válida" ]);
            } 
            
        }else{
            $this->updateConsulta($bus,$mensaje,$fech_hor,$rol);
            throw ValidationException::withMessages([ "Error"=>"Los datos enviados son incorrectos, intente enviar datos válidos."]);
        }

        $this->updateConsulta($bus,$mensaje,$fech_hor,$rol);
        
        
    }

    function validarSearchMacAddress($bus,$tipoBus,$fech_hor,$rol){

        $mensaje = "";

        if (preg_match("/^[a-zA-Z0-9\:]+$/", $bus) == 1) {
            
            if(strlen($bus)<17 || strlen($bus)> 17){
              $mensaje = 'La MacAddress Es inválida'; 
              $this->updateConsulta($bus,$mensaje,$fech_hor,$rol);
              throw ValidationException::withMessages([ "texto" => "El campo de búsqueda es invalido" ]);
            }
           if($tipoBus == "seleccionar" || ($tipoBus != 1 && $tipoBus != 2 && $tipoBus != 3 && $tipoBus != 4)){  
              $mensaje = 'El tipo de busqueda no es válido';
              $this->updateConsulta($bus,$mensaje,$fech_hor,$rol);   
              throw ValidationException::withMessages([ "tipo" => "Seleccione un tipo de busqueda válida" ]);
            } 
  
          }else{
              $this->updateConsulta($bus,$mensaje,$fech_hor,$rol);
              throw ValidationException::withMessages([ "Error"=>"La MacAddress Es inválida. Veerifique que sea un cliente válido."]);
          }

          $this->updateConsulta($bus,$mensaje,$fech_hor,$rol);

    }

    function updateConsulta($bus, $mensaje, $fech_hor,$rol) {
        if (($rol == "1463" || $rol == "CALL" || $rol == "ATENTO" ) && $bus > 0) {
     
            DB::update("update multiconsulta.multi_consultas 
                                SET mensaje=? 
                                WHERE dato=? AND fechahora=?",
                            [$mensaje,$bus,$fech_hor]);  
         }
    }

    function ArmandoQuery($tipoBus,$bus){
        $queryresult = array();   
        switch ($tipoBus) {
          case 1 :
              $queryresult["filtroWhere"] = " AND a.IDCLIENTECRM=$bus ";
              $queryresult["TipBus"] = "IDCLIENTECRM";
              $queryresult["limit"] = " ";
              break;
          case 2 :
              $queryresult["filtroWhere"] = " AND a.`mac3`='" . str_replace('-', '', str_replace(':', '', str_replace('.', '', $bus))) . "' ";
              $queryresult["TipBus"] = "MACADDRESS";
              $queryresult["limit"] = " LIMIT 1 "; 
              break;
          case 3 :
              $queryresult["filtroWhere"] = " AND (a.`telf1`='$bus' or a.`telf2`='$bus' or a.`movil1`='$bus' )";
              $queryresult["TipBus"] = "TELEFONO TBA/CEL";
              $queryresult["limit"] = "  ";
              break;
          case 4 :
              $queryresult["filtroWhere"] = " and h.`telefonohfc`='$bus'";
              $queryresult["TipBus"] = "TELEFONO HFC";
              $queryresult["limit"] = "  "; 
              break; 
        default: 
            throw new HttpException(422,"El tipo de busqueda no existe en el sistema.");
       }
       return $queryresult;

    }

    function queryPrincipal($filtroWhere,$limit){

        
        $qprinc = DB::select("
                    SELECT a.codserv,a.IDCLIENTECRM,a.estado AS estadoserv ,a.idservicio, a.idproducto, a.idventa,a.amplificador,a.idproductomta,
                    a.nameclient AS Nombre, a.telf1, a.telf2,a.movil1, a.MACADDRESS,a.IPCM,
                    f.Fabricante, f.Modelo,
                    f.Versioon AS Version_firmware, if(st.cmts is null,if(nv.macaddress is null,st.cmts,nv.cmts),st.cmts) as cmts,a.mtamac,IF(a.mtamac<>'N/D','Cliente Tiene VOIP','Cliente No Tiene VOIP') AS voip,
                    IF(g.codreqmnt >0,g.codreqmnt,0) AS num_masiva,h.telefonohfc, a.mac2, n.veloc_comercial,'' AS fecha_corte,
                    IF(a.estado='Inactivo','Cortado','Nada') AS corte, a.scopesgroup ,
                    IF(px.nodo IS NOT NULL,'TRABAJO PROGRAMADO','') AS trab,px.TIPODETRABAJO,
                    a.nodo AS NODO,a.troba AS TROBA, n.velocidad_final AS SERVICEPACKAGE,n.SERVICEPACKAGECRMID AS SERVICEPACKAGECRMID,
                    n.velocidad_final ,IF(st.macstate LIKE '%nline%' ,CONCAT(k.tipopuerto,''),'') AS saturado,
                    IF(ll.tipo='CAIDA MASIVA','Caida',IF(ll.tipo='CAIDA AMPLIF','Caida Amplif',
                    IF(ll.tipo='CAIDA SENAL','Señal_RF',IF(ll.tipo='CAIDA SENAL AMPLIF','Señal RF Amplif','')))) AS cliente_alerta
                    , TRIM(a.IPCM) AS IPAddress,
                    IF(a.cmts='HIGUERETA3' AND SUBSTR(a.f_v,1,6) IN ('C5/0/0','C5/0/1','C5/0/2'), ss.description,CONCAT(a.nodo,' : ',a.troba)) AS Nodo_Troba,
                    TIMEDIFF(NOW(),CONCAT(SUBSTR(g.fecreg,7,4),'-',SUBSTR(g.fecreg,4,2),'-',SUBSTR(g.fecreg,1,2),' ',SUBSTR(g.fecreg,12,8))) AS tiempo_masiva,
                    IF(cc.entidad IS NOT NULL ,'CLIENTE INFLUYENTE','') AS tipocli,
                    IF(nv.Interface IS NOT NULL,TRIM(nv.Interface),TRIM(st.Interface)) AS interface,
                    nv.USPwr,nv.USMER_SNR,nv.DSPwr,nv.DSMER_SNR,st.RxPwrdBmv,st.IPAddress,
                    IF(st.MACState LIKE '%nline%','online',IF(st.macstate IS NULL,IF(nv.`MACAddress` IS NULL,'','online'),st.macstate)) AS MACState,f.docsis,a.naked,'' AS velocidad_actual,
                    IF(cv.codigo IS NOT NULL,'CONVERGENTE','') AS convergente
                    FROM multiconsulta.nclientes a
                            LEFT JOIN ccm1_data.marca_modelo_docsis_total f  ON a.MACADDRESS=f.MACAddress
                            LEFT JOIN catalogos.velocidades_cambios n ON a.SERVICEPACKAGE=n.SERVICEPACKAGE
                            LEFT JOIN dbpext.masivas_temp g ON a.nodo=g.codnod AND a.troba=g.nroplano
                            LEFT JOIN catalogos.telefonoshfc h ON a.MACADDRESS=h.macaddress
                            LEFT JOIN dbpext.trabajos_pendientes_view px  ON a.nodo=px.nodo AND a.troba=px.troba
                            LEFT JOIN reportes.clientes_en_puerto_saturado k  ON a.MACADDRESS=k.macaddress
                            LEFT JOIN alertasx.clientes_alertados ll ON a.MACADDRESS = ll.macaddress
                            LEFT JOIN catalogos.etiqueta_puertos ss ON a.cmts=ss.cmts AND a.f_v=ss.interface
                            LEFT JOIN reportes.criticos cc ON a.idclientecrm=cc.idclientecrm
                            LEFT JOIN ccm1.scm_phy_t nv ON a.mac2=nv.macaddress
                            LEFT JOIN ccm1.scm_total st ON a.mac2=st.macaddress
                            LEFT JOIN catalogos.convergente cv ON a.idclientecrm=cv.codigo
                        WHERE 1=1 $filtroWhere 
                        GROUP BY a.`IDCLIENTECRM`,5,6  $limit");
                 
          return $qprinc;
    }

    function resBusVarios($bus){
        $cad =  DB::select(
                 "SELECT 
                 a.IDCLIENTECRM,
                 f.Nombre,
                 a.telf1,
                 a.telf2,
                 a.MACADDRESS,
                 a.SERVICEPACKAGE,
                 a.cmts AS cmts1,
                 a.f_v AS interface,
                 c.IPAddress,
                 c.MACState,
                 f.Fabricante,
                 f.Modelo,
                 f.Versioon AS Version_firmware,
                 f.cmts,
                 a.direccion
                 FROM multiconsulta.nclientes a 
                 LEFT JOIN ccm1.scm_total c 
                 ON a.mac2=c.macaddress
                 LEFT JOIN ccm1_data.marca_modelo_docsis_total_final f
                 ON a.MACADDRESS=f.MACAddress
                 WHERE 1=1 AND a.idclientecrm=?", [$bus]);
        
             return $cad;
    }

    function validaDigitalizacion($nodo,$troba){
    
        $msjDigi='';
        $resTrabDigi = DB::select("
        SELECT concat(MENSAJE,' : REALIZADA EL : ',substr(fecha_registro,1,10)) as MENSAJE 
        FROM dbpext.digitalizacion_view WHERE nodo=?
           AND troba=? LIMIT 1",[$nodo, $troba]);
           
        if(!empty($resTrabDigi)){
            $msjDigi = trim($resTrabDigi[0]->MENSAJE);
        }
         
        return $msjDigi;
    }

    function validaTrabProg($nodo,$troba,$bus,$mensaje,$fech_hor,$area,$corte,$trab){
        $rowTrabProg='';
        $msjDigi='';
        $esTrabProg =0;
 
        $resTrabProg= DB::select("
        SELECT 'SI' as tp  FROM dbpext.trabajos_programados_noc WHERE nodo=?
        AND troba=? and estado='ENPROCESO' LIMIT 1", [$nodo,$troba]);
  
        if(!empty($resTrabProg)){
          //$rowTrabProg = $resTrabProg[0]->tp."</br>".$msjDigi;
          $rowTrabProg = $resTrabProg[0]->tp;
        }
       
        if ($rowTrabProg == 'SI') {
            $esTrabProg = 1;
        } 
        elseif ($trab == 'TRABAJO PROGRAMADO') {
              // Es masiva 
                  $esTrabProg = 1;
                  $mensaje='';
                  
                  if ($corte <> 'Cortado') {
                    $esTrabProg = 1;
                    $mensaje = 'Troba dentro de Trabajos Programados';
                  }
                  
              //Guarda consulta
                $this->updateConsulta($bus,$mensaje,$fech_hor,$area);
                
                
                
        }
       
        return $esTrabProg;
    }

    function validarServicio($nodo,$troba,$area,$corte,$trab,$ttrab,$num_masiva,$tiempo_masiva,$cliente_alerta){
        $mensaje='';
        $sw=0;
          if($corte=='Cortado' && $sw==0)
            {$sw=1;$mensaje='Cliente con corte de servicio ejecutado en Intraway M1';}
          if($corte<>'Cortado' && $trab<>'' && $sw==0)
            {$sw=1;$mensaje='Troba dentro de Trabajos Programados :</br>'.$ttrab."</br>Nodo:".$nodo." Troba:".$troba;}
          if($corte<>'Cortado' && $trab=='' && $sw==0 && $cliente_alerta<>'' && $num_masiva*1==0)
            {if($cliente_alerta=='Caida' || $cliente_alerta=='Caida Amplif' ){$msjx=$cliente_alerta;} else {$msjx=$cliente_alerta;}
            $sw=1;$mensaje='Alerta de '.$msjx.' Generar averia R417 </br> Problemas en el servicio detectado.'."</br>Nodo:".$nodo." Troba:".$troba;}
          if($corte<>'Cortado' && $trab=='' && $sw==0 && $cliente_alerta<>'' && $num_masiva*1>0)
            {$sw=1;$mensaje="Generar averia R417 </br>Problema con su Servicio :<br/>Averia Nro:" . $num_masiva . "</br> Tiempo de incidencia: ".substr($tiempo_masiva,0,8)."</br>Nodo:".$nodo." Troba:".$troba;}
          if($corte<>'Cortado' && $trab=='' && $sw==0 && $cliente_alerta=='' && $num_masiva>0)
            {$sw=1;$mensaje="Generar averia R417 </br>Problemas en el Servicio de TV <br/>Averia Nro:" . $num_masiva . "</br> Tiempo : ".substr($tiempo_masiva,0,8)."</br>Nodo:".$nodo." Troba:".$troba;}
          return $mensaje;
    }

    function validaMsjPer($cmts,$cmtspuerto,$nodo,$troba,$hoy){
      $ms = DB::select("
            select msj from multiconsulta.tbmsj
            where ( (cmts=? and cmts<>'')  or  
                    (ptocmts=? and ptocmts<>'') or 
                    (nodo=? and troba=? and nodo<>'' and troba<>'') or 
                    (nodo=? and nodo<>''  and troba='')
                  ) and fechahorafin>=?",[$cmts,$cmtspuerto,$nodo,$troba,$nodo,$hoy]); 
      
      $msj=empty($ms)? '' : $ms[0]->msj;
      return $msj;
    }

    function validaMta($mtamac,$telefonohfc){
      $tipoprob='';
      if($mtamac=='N/D' && $telefonohfc>0){
        $tipoprob='Mta No Provisionado en Intraway </br> Pasar a Torre de Control HFC</br>';
      }
      return $tipoprob;
    }

    function validaNiveles($downPx,$upSnr,$downSnr,$upPx,$cliente_alerta,$nodo,$troba,$macstate,$num_masiva){
      $tipoprob='';
      $niveles='ok';
     
          if (($cliente_alerta == 'Caida' || $cliente_alerta == 'Señal RF' || $cliente_alerta == 'Caida Amplif' || $cliente_alerta == 'Señal RF Amplif' ) && $niveles==''){
             //echo "Entra";
          if ($downPx < -5 || $downPx > 10) {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
          }
          if ($upSnr * 1 < 27 ) {
              $tipoprob = 'Generar averia R417 </br>Probable problema de Pext';
              $niveles = 'Malos';
          }
          if ($upSnr * 1 < 27 and $upPx * 1<36 ) {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
            //echo "entra";
          }
          if ($downSnr * 1 < 29 ) {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
          }
          if ($downPx * 1 <= - 5 || $downPx * 1 > 12)  {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
          }
          if ($upPx * 1 <= 35 || $upPx * 1 > 55)  {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
          }
          if ($downPx * 1 > 10 && $upPx * 1 <= 36  ) {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
          }
          if ($downPx * 1 > 8 && $downSnr * 1 < 30 ) {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
          }
          if ($upPx * 1 < 35 && $upPx * 1 > 0 ) {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
          }
          if ($downPx * 1 > 15 ) {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
          }
          if ($upSnr * 1 < 27 && $downSnr * 1 > 30 && $downPx * 1 >= - 10 && $downPx * 1 <= 12 && $upPx * 1 >= 37 && $upPx * 1 <= 55) {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
          }
          if ($upSnr * 1 > 27 && $downSnr * 1 < 30 && $downPx * 1 >= - 10 && $downPx * 1 <= 12 && $upPx * 1 >= 37 && $upPx * 1 <= 55 ) {
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
              $niveles = 'Malos';
          }
          if ($downPx * 1 < - 15 || $downPx * 1 > 15) {
              $niveles = 'Malos';
              $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
          }
        }
        // Hasta aqui - Problemas de Planta
        ///************************** */
        if (($cliente_alerta <> 'Caida' && $cliente_alerta <> 'Señal RF' && $cliente_alerta <> 'Caida Amplif' && $cliente_alerta <> 'Señal RF Amplif' ) && $niveles<>'Malos'){
          if (($downPx < - 5 && $upPx > 55)) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
          if (($downPx < - 5 || $downPx > 10)) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
          if ($downPx < - 5 && $downSnr < 30 ) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
          if ($upSnr * 1 < 27 ) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
          if ($downSnr * 1 < 29) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
          if (($downPx * 1 <= - 5 || $downPx * 1 > 12)) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
          if (($upPx * 1 <= 35 || $upPx * 1 > 55)) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
          if ($downPx * 1 < - 10 && $upPx * 1 > 55 ) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'malos';
          }
          if ($downPx * 1 > 8 && $downSnr * 1 < 30 ) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
          if ($downPx * 1 > 15 ) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
          if ($upSnr * 1 < 27 && $downSnr * 1 > 30 && $downPx * 1 >= - 10 && $downPx * 1 <= 12 && $upPx * 1 >= 37 && $upPx * 1 <= 55) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
          if ($upSnr * 1 > 27 && $downSnr * 1 < 30 && $downPx * 1 >= - 10 && $downPx * 1 <= 12 && $upPx * 1 >= 37 && $upPx * 1 <= 55 ) {
              $tipoprob = 'Probable averia en:Red Cliente';
              $niveles = 'Malos';
          }
          if ($downPx * 1 < - 15 || $downPx * 1 > 15) {
              $niveles = 'Malos';
              $tipoprob = 'Probable averia en:Red Cliente';
            
          }
        }
        if($downPx=='' and $downSnr==''  && $macstate == 'online'){$tipoprob ='';}
  
        //Validacion de estado Init para mensaje de generacion de averias y derivacion a badeja 415
        if ( ($macstate == "init(d)" || $macstate == "init(i)"   || $macstate == "init(io)"  || $macstate == "init(o)"     || 
            $macstate == "init(r)"  || $macstate == "init(r1)"  || $macstate == "init(t)"   || $macstate == "bpi(wait)")   
            && $num_masiva * 1==0 ){
          $tipoprob = 'Probable averia en:Red Cliente';
        }
        // Fin de validacion de Init
        
        ///
        $amasiv = DB::select(
          "select 'SI' as averia 
          FROM alertasx.caidas_t a 
          WHERE a.nodo=? AND a.troba=? AND Caida='SI' 
          limit 1",[$nodo,$troba]);
        $masi = empty($amasiv) ? "" : $amasiv[0]->averia;
        
        ///
        $amasiv_amp = DB::select(
          "select 'SI' as averia 
          FROM alertasx.caidas_new_amplif a 
          WHERE a.nodo=? AND a.troba=? AND estado='CAIDO'  
          limit 1",[$nodo,$troba]);
        $masi_amp = empty($amasiv_amp) ? "" : $amasiv_amp[0]->averia;
  
        if($masi_amp == 'SI' && ($macstate=='offline' || $macstate == "init(d)" || $macstate == "init(i)" || $macstate == "init(io)" || $macstate == "init(o)" || $macstate == "init(r)" || $macstate == "init(r1)"  || $macstate == "init(t)" || $macstate == "bpi(wait)")){
           $tipoprob = 'Probable problema de Pext - Amplif';
        }
  
        if($masi == 'SI'   && ($macstate=='offline' || $macstate == "init(d)" || $macstate == "init(i)" || $macstate == "init(io)" || $macstate == "init(o)" || $macstate == "init(r)" || $macstate == "init(r1)"  || $macstate == "init(t)" || $macstate == "bpi(wait)")){
            $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
        }
  
        if(($cliente_alerta == 'Caida Amplif' || $cliente_alerta == 'Señal RF Amplif') &&  $niveles<>'ok'){
            $tipoprob = 'Probable problema de Pext - Amplif';
        }
  
        if(($cliente_alerta == 'Caida' || $cliente_alerta == 'Señal RF' )  &&  $niveles<>'ok'){
            $tipoprob = 'Generar averia R417 </br> Probable problema de Pext';
        }
  
        if($niveles=='ok'){ $tipoprob='';}
  
          return $tipoprob;
    }

    function verMacIpPe($fabricante,$ipaddress,$docsis)
    {
        $verMacIpPeR = array();

        $fabricante_substr = substr($fabricante,0,5);
        $oidx='iso.3.6.1.2.1.4.34.1.10.1.4';

        if($fabricante_substr=="Arris"){
            $oidx='iso.3.6.1.2.1.4.20.1.1';
        }
        if($fabricante_substr=="Hitro"){
            $oidx='iso.3.6.1.2.1.4.22.1.1.1';
        }

        // Proceso para obtener la ip publica o cgnat
        $verMacIpPeR["publica"]="";
        $verMacIpPeR["macx"]="";
        $verMacIpPeR["macmta"]="";
        $verMacIpPeR["ipmta"]="";

        $cpe=array();
       
        if ($ipaddress<>'0.0.0.0'){//inicio if
            $ippu="snmpwalk  -c MODEM8K_PILOTO -v2c ".$ipaddress." ".$oidx;
            //echo $ippu;
           
            //$reg=array();
            exec($ippu,$cpe);
            
            $regy=array();
            
            $reg ='';
            //echo $ippu;4
            $cantidad_cpe = count($cpe);
            for ($i=0;$i<$cantidad_cpe;$i++){
                $regy = $cpe[$i];
                $cad='';
                if($fabricante_substr=="Arris"){
                    $cad=substr($regy,23,3);
                    //echo $cad."</br>";
                    //echo $regy;
                }else {
                    if($fabricante_substr=="Hitro"){
                        $cad=substr($regy,26,3);
                    }else{
                        $cad=substr($regy,28,3);
                    }
                }
                if ($cad<>"10." && $cad<>'127' && $cad<>'192' && trim($cad)<>''){
                    $reg=trim(str_replace("= INTEGER: 1","",$cpe[$i]));
                    //echo $reg."</br>";
                }
            } 
            //echo "el reg es: ".$reg;
            //dd($reg);
            $oid ='';
            $publica='';
            if($fabricante_substr=="Arris"){
                $oid="iso.3.6.1.2.1.2.2.1.6.10";
                $publica=substr($reg,23,15);//si se utiliza 
            }
            else {
                if($fabricante_substr=="Hitro"){
                        $oid="iso.3.6.1.2.1.4.22.1.2.1.".substr($reg,25,14);
                        $publica=substr($reg,25,15); 
                    } elseif(strlen($reg)>10){
                        $oid="iso.3.6.1.2.1.4.22.1.2.1.".substr($reg,28,15);
                        $publica=substr($reg,28,15); 
                        }
                        
            }

            // Aqui obtenemos la MAC CPE
            $maccpe=array();
            $snmp='snmpget -c MODEM8K_PILOTO -v2c '.$ipaddress.' '.$oid;
            exec($snmp,$maccpe);
            //dd($maccpe);
            //echo "\nComando snmp --->".$snmp;
            $macaddress='';
            $cpex=array();
            $regx=array();
            $reg1x=array();
            $reg2x=array();
            $regx = empty($maccpe[0])? '' : $maccpe[0];
            $macx='';
            
            if($regx != ''){
                $regxx = explode(":", $regx);
                
                //print_r($regxx);
                foreach ($regxx as $fil2x) {
                    if ($fil2x!=''){
                            $reg2x[] = $fil2x;
                    }  
                }
                //dd($reg2x);
                $macx= empty($reg2x[1])? '' : substr(str_replace(' ',':',$reg2x[1]),1,20);//si se utiliza
            }else{
                $reg2x[] = "";
            }
            
            
            
            if(trim(substr($publica,1,2))==''){
                $macx='';
                $publica='';
            }
            $mta1=array();
            $macmta="snmpwalk -c MODEM8K_PILOTO -v2c ".$ipaddress." iso.3.6.1.2.1.4.22.1.2.16"; // si se utiliza
            exec($macmta,$macmtax);
            if(isset($macmtax[0])){
                $mta1=$macmtax[0];
                $mta2 = explode("=", $mta1);
                $ipmta=substr($mta2[0],26,16); //si se utiliza
                $macmta=str_replace(" ",":",substr($mta2[1],13,17));
            }else{
                $ipmta='';
                $macmta='';
            } 
            if($docsis=='DOCSIS2'){
                $ipmta='';
                $macmta='';
            }
                $verMacIpPeR["publica"]=$publica;
                $verMacIpPeR["macx"]=$macx;
                $verMacIpPeR["macmta"]=$macmta;
                $verMacIpPeR["ipmta"]=$ipmta;

                return $verMacIpPeR;

        }//fin if
    }

    function ultimoRequerimiento($bus){
        $msj = '';
           
            try { 
              $x = DB::select(
                "select a.codigo_req  as codreq,a.codigo_tipo_req,a.codigo_motivo_req,b.des_motivo,a.fecha_liquidacion
                FROM cms.prov_liq_catv_pais a
                INNER JOIN cms.cms_tiporeq_motivo b
                ON a.codigo_tipo_req=b.tipo_req
                AND a.codigo_motivo_req=b.motivo
                WHERE a.codigo_del_cliente = ? and datediff(now(),fecha_liquidacion)<=7 
                order by a.fecha_liquidacion", [$bus]);

            } catch(QueryException $ex){ 
              //dd($ex->getMessage()); 
              throw new HttpException(422,"Problemas con la red, intente nuevamente.");
              // Note any method of class PDOException can be called on $ex.
            }
         
        $x2 = empty($x[0]->codreq)? 0 : $x[0]->codreq;
        if ($x2 > 0) {
            $msj = "ULT.REQ:" . $x[0]->codreq . " EL DIA :" . $x[0]->fecha_liquidacion . "</br>TPO_REQ:" . $x[0]->codigo_tipo_req . " " . $x[0]->codigo_motivo_req . " : " . $x[0]->des_motivo . "</br>" . "<font color=yellow size=1>Si el cliente reclama por Inst. de deco o Ctrl Rmto - Generar Rutina</font>";
        }
      
        return $msj; 
    }

    function validaObsoleto($marca,$model){

        try { 
            $obso = DB::select(
              "select COUNT(*) AS obsoleto 
                FROM ccm1_data.cm_obsoletos_tabla a 	
                WHERE  REPLACE(a.fabricante,' ','')=REPLACE(?,' ','') 
                AND REPLACE(modelo,' ','')=REPLACE(?,' ','')", [$marca,$model]);

          } catch(QueryException $ex){ 
            //dd($ex->getMessage()); 
            throw new HttpException(422,"Problemas con la red, intente nuevamente.");
            // Note any method of class PDOException can be called on $ex.
          }

       // $obso="SELECT COUNT(*) AS obsoleto FROM ccm1_data.cm_obsoletos_tabla a 	WHERE  REPLACE(a.fabricante,' ','')=REPLACE('$marca',' ','') AND REPLACE(modelo,' ','')=REPLACE('$model',' ','')";
      //echo $obso;
       
      $obso3 = empty($obso[0]->obsoleto)? 0 : $obso[0]->obsoleto;
 
      $obsoleto =  $obso3>=1  ? 'SI' : 'NO';
       
      return $obsoleto;
}
 

    function procesarMulticonsulta($recordP){
  
         //PRIMERAS DECLARACIONES
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
 
        $recordP[0]->rol = $rolNombre;//Area
        $recordP[0]->corte = trim($recordP[0]->corte);//limpiamos
        $recordP[0]->esMasiva = 0;  //declaramos masiva como 0
        $recordP[0]->resultadoAlerta="";  //declaramos resultado Alerta como vacio
        $recordP[0]->mensajeGeneral = ''; //declaramos mensaje General como vacio
        $recordP[0]->mensajeMasiva = '';  //declaramos mensaje Masiva como vacio
        $recordP[0]->esTrabProg = 0;  //si es trabajo prog
        $recordP[0]->ipcm = $recordP[0]->IPAddress;
        $obsoleto=$this->validaObsoleto($recordP[0]->Fabricante,$recordP[0]->Modelo);
        $recordP[0]->obsoleto = $obsoleto;
        
        if($recordP[0]->ipcm=='') $recordP[0]->ipcm = $recordP[0]->IPCM;
        if($recordP[0]->IPAddress=='') $recordP[0]->IPAddress =$recordP[0]->ipcm;
        $suspendido = "";
        $fech_hor = date("Y-m-d H:i:s"); //esto debe ser estatico y global por consulta

        
        if ($recordP[0]->corte == 'Cortado') {
          $recordP[0]->esMasiva = 1;//declaramos masiva como 1
        }
 
         //Valida Servicio si esta en corte, caida o averia  
         $resultado = $this->validarServicio($recordP[0]->NODO,$recordP[0]->TROBA,$rolNombre,$recordP[0]->corte,
                                            $recordP[0]->trab,$recordP[0]->TIPODETRABAJO,$recordP[0]->num_masiva,
                                            $recordP[0]->tiempo_masiva,$recordP[0]->cliente_alerta);
         
         
       if($resultado<>''){
          $recordP[0]->esMasiva = 1;//declaramos masiva como 1
          $recordP[0]->mensajeGeneral=$resultado;  //mensaje obtiene el resultado de servicio 
          if($resultado=='Cliente con corte de servicio ejecutado en Intraway M1') $suspendido = $resultado;
        }
        
         
        if($resultado=='' && $suspendido==''){  
          $hoy=date("Y-m-d H:i:s");
          $cmts_puerto=$recordP[0]->cmts.'-'.$recordP[0]->interface;
          
          $resultado=$this->validaMsjPer($recordP[0]->cmts,$cmts_puerto,$recordP[0]->NODO,$recordP[0]->TROBA,$hoy);
         
          if($resultado<>''){
            $recordP[0]->esMasiva = 1;//declaramos masiva como 1
            $recordP[0]->mensajeGeneral=$resultado;  //mensaje obtiene el resultado de servicio
          }
        }
        
 
         //gg

        $validacionDigital = $this->validaDigitalizacion($recordP[0]->NODO,$recordP[0]->TROBA);//Valida digitalización
        $recordP[0]->mensajeDigital = $validacionDigital; //verificar si se usa

        //Valida Trabajo Programado
        $esTrabProg=$this->validaTrabProg($recordP[0]->NODO,$recordP[0]->TROBA,$recordP[0]->IDCLIENTECRM,
                                        $recordP[0]->mensajeGeneral,$fech_hor,$rolNombre,$recordP[0]->corte,$recordP[0]->trab);


        if ($validacionDigital<>''){
            $recordP[0]->mensajeGeneral = ""; //Se limpia del valida msj Per
            if ($esTrabProg==0) {
                $recordP[0]->esTrabProg = 1; 
            }
        }  
 
        if((int)$recordP[0]->num_masiva > 0) $recordP[0]->mensajeMasiva = "Averia Num:".$recordP[0]->num_masiva;

        //Limpiamos el Mensaje General
       /// $recordP[0]->mensajeGeneral = ""; // Se limpia para validar otros procesos
        
       
        //Para obtener phy desde el cmts;
        //SEGUNDAS  DECLARACIONES
        $npwr_up =  $recordP[0]->USPwr;
        $nsnr_up =  $recordP[0]->USMER_SNR;
        $npwr_dn =  $recordP[0]->DSPwr;
        $nsnr_dn =  $recordP[0]->DSMER_SNR;
        $tipoprob = '';
        $niveles = 'ok';
 
        if ($recordP[0]->MACState == 'offline' || $recordP[0]->MACState == '') {
          $npwr_dn = 0;
          $nsnr_dn = 0;
        }

        //TRABAJANDO CON DOWNSTREAM y UPSTREAM 
        $downPx = $npwr_dn;
        $upPx = $npwr_up;
        $downSnr = $nsnr_dn;
        $upSnr = $nsnr_up;
        $errorMta=$this->validaMTA($recordP[0]->mtamac,$recordP[0]->telefonohfc);
        
        $tipoprob=$this->validaNiveles($downPx,$upSnr,$downSnr,$upPx,$recordP[0]->cliente_alerta,
                                        $recordP[0]->NODO,$recordP[0]->TROBA,$recordP[0]->MACState,$recordP[0]->num_masiva);
         
        if($suspendido<>'') $tipoprob=$suspendido;

        if($resultado<>'' && $suspendido=='' && $recordP[0]->MACState<>'') {
          $tipoprob='';
        }else{
          $resultado=$tipoprob;
        }
         
       
        if($tipoprob<>''){
	
          $niveles='Malos';
          $recordP[0]->mensajeGeneral =$tipoprob;
      
          $this->updateConsulta($recordP[0]->IDCLIENTECRM,$recordP[0]->mensajeGeneral,$fech_hor,$rolNombre);
        }

          
        $publica="";
        $macx="";
        $macmta="";
        $ipmta="";
        $msj='';
        $mostrar = '.';
        $playa = '';
 

        if ($recordP[0]->MACState == 'online' && strlen(trim($resultado))== 0 && strlen(trim($tipoprob))==0) {
            $fabricante=$recordP[0]->Fabricante; 
            
              $mac_ip_cpe = $this->verMacIpPe($fabricante, $recordP[0]->IPAddress,$recordP[0]->docsis);
              $publica= $mac_ip_cpe["publica"];
              $macx= $mac_ip_cpe["macx"];
              $macmta= $mac_ip_cpe["macmta"];
              $ipmta= $mac_ip_cpe["ipmta"];
            //echo "ingresaste con 2053683";
        } else {
            if($recordP[0]->MACState <> 'online'){
                $publica='no'; 
            } else {
              $fabricante=$recordP[0]->Fabricante;  
                $mac_ip_cpe = $this->verMacIpPe($fabricante, $recordP[0]->IPAddress,$recordP[0]->docsis);
                $publica= $mac_ip_cpe["publica"];
                $macx= $mac_ip_cpe["macx"];
                $macmta= $mac_ip_cpe["macmta"];
                $ipmta= $mac_ip_cpe["ipmta"];
            }
        }
 

        // Fin de ips

        if ($resultado<>'' && $tipoprob<>'' && 
            (($downSnr == '-' || $downSnr == '-----') || ($upSnr + $downSnr + $downPx + $downPx + $upPx + $upPx * 1 == 0)) && 
            trim($recordP[0]->interface) <> '' && $tipoprob == '') {
            $tipoprob = "Sop||te / Back Office";
        }

        if ($resultado<>'' && $tipoprob<>'' && 
            (($downSnr == '-' || $downSnr == '-----') || ($downSnr + $downPx + $upPx == 0)) &&
            trim($recordP[0]->interface) == '' && $tipoprob == '') {
            $tipoprob = "Soporte / Back Office";
            // $phy = 1; no se usa nunca
            $niveles = 'Malos';
        }

        if ($niveles == "ok" && $tipoprob <> '') {
            $tipoprob == '';
        }
         
          
        ##Obtiene el ultimo requerimiento atendido al cliente
           
        if ($niveles == "ok" && $tipoprob == '') { 
            $msj=$this->ultimoRequerimiento($recordP[0]->IDCLIENTECRM);
              
        } 

        
         //Guardamos Resultado como alerta
         $recordP[0]->resultadoAlerta=$resultado;
        
 
         return $recordP;


        ## Fin de validaciones ################################################################
       /*             
          if ($recordP[0]->MACState == 'offline') {
              $npwr_dn = 0;
              $nsnr_dn = 0;
            }

        ##### Inicio DIV MASIVAS

        if($resultado <> ''){
            $mostrar=$resultado;
            $this->updateConsulta($recordP[0]->IDCLIENTECRM,$recordP[0]->mensajeGeneral,$fech_hor,$rolNombre);
            if ($mostrar<>'.' && $mostrar<>''){
                $recordP[0]->mensajeGeneral  = $mostrar;
            } else {
                $recordP[0]->mensajeGeneral  = '';
            }
        
        }*/

        ##### Fin Div de masivas
 

    }

}
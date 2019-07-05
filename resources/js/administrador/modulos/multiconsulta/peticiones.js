import filters from '@/globalResources/lists/filters'
import errors from '@/globalResources/errors'

const peticiones = {}

peticiones.searchCountMulticonsulta = function searchCountMulticonsulta(tipo,valor,callBack)
{
  
  //search
    
  //console.log("la data que se enviara sera: ", tipo,valor)
 
  $.ajax({
    url:`/administrador/multiconsulta/search/count`,
    method:"GET",
    async: true,
    data:{
        type_data:tipo,
        text:valor
    },
   cache: false, 
   dataType: "json", 
  })
  .done(function(data){ 
    //console.log("callbak antes del envio:",data)
 
    return callBack(data);
     
  })
  .fail(function(jqXHR, textStatus, errorThrown){
     // console.log( "Request failed: " ,textStatus ,jqXHR,errorThrown);
       
      return callBack({
        "error":"failed",
        "jqXHR":jqXHR,
        "textStatus":textStatus,
        "errorThrown":errorThrown,
      });
      
  }); 

}

peticiones.armandoMultiplesResultados = function armandoMultiplesResultados(data){

    let idCliente =``
    let nombreCliente = ``
    let tabla_multiple = `<div class="w-100 result-form-multi">
                            <h3 class="text-center text-uppercase">Seleccionar Cliente</h3>
                            <div  class="div_busqueda table-responsive">
                                    <table class="table table-bordered table-hover tabla_multiple_data_cli" id="tablaresvarios">
                                        <thead>
                                            <tr>
                                                <th class="celda_titulo" >Mac Address</th>
                                                <th class="celda_titulo" >Service Package</th>
                                                <th class="celda_titulo" >CMTS</th>
                                                <th class="celda_titulo" >Interface</th>
                                                <th class="celda_titulo" >MaC State</th>
                                                <th class="celda_titulo" >Fabricante</th>
                                                <th class="celda_titulo" >Modelo</th>               
                                                <th class="celda_titulo" >Direccion</th>
                                            </tr>
                                        </thead>
                                        <tbody>`
                                        data.forEach(el => {
                                            tabla_multiple += `<tr>`
                                            tabla_multiple += `<td class="celda2" >${el.MACADDRESS}</td>`
                                            tabla_multiple += `<td class="celda2" >${el.SERVICEPACKAGE}</td>`
                                            tabla_multiple += `<td class="celda2" >${el.cmts1}</td>`
                                            tabla_multiple += `<td class="celda2" >${el.interface}</td>`
                                            tabla_multiple += `<td class="celda2" >${el.MACState}</td>`
                                            tabla_multiple += `<td class="celda2" >${el.Fabricante}</td>`
                                            tabla_multiple += `<td class="celda2" >${el.Modelo}</td>`
                                            tabla_multiple += `<td class="celda2" >${el.direccion}</td>`
                                            tabla_multiple += `</tr>`
                                            if (el.IDCLIENTECRM != null && el.IDCLIENTECRM > 0) idCliente =  el.IDCLIENTECRM 
                                            if (el.Nombre != null && el.Nombre.length > 0 )  nombreCliente = el.Nombre
                                        }); 
    tabla_multiple += ` </tbody>
                                        
                                    
                                    </table>
                                    <div id="info_client_multiple">
                                    <span class="campo">Código:</span> <span class="result">${idCliente}</span>
                                    <span class="campo">Cliente:</span> <span class="result">${nombreCliente}</span>
                                    </div>
                            </div>
                        </div>`

    $("#multiple_result").html(tabla_multiple)
    $("#searchModal").modal("show")
}

peticiones.searchMulticonsultaByMacAddress = function searchMulticonsultaByMacAddress(tipo,valor,callBack)
{
    console.log("la data que se enviara sera: ", tipo,valor)
 
    $.ajax({
      url:`/administrador/multiconsulta/search`,
      method:"GET",
      async: true,
      data:{
          type_data:tipo,
          text:valor
      },
     cache: false, 
     dataType: "json", 
    })
    .done(function(data){ 
      //console.log("callbak antes del envio:",data)
   
      return callBack(data);
       
    })
    .fail(function(jqXHR, textStatus, errorThrown){
       // console.log( "Request failed: " ,textStatus ,jqXHR,errorThrown);
         
        return callBack({
          "error":"failed",
          "jqXHR":jqXHR,
          "textStatus":textStatus,
          "errorThrown":errorThrown,
        });
        
    }); 
}

peticiones.imprimirMulticonsultaResult = function imprimirMulticonsultaResult(data) {
    console.log("la data a impirmir es: ", data)
    let tablaResult = `<div class="col-md-10 result-form-multi d-flex justify-content-center flex-wrap flex-column p-0">`

    tablaResult+=`<div class="parpadea parpadea-text"> 
                        <td>Aqui mensaje de averia</td> 
                    </div>`
    tablaResult+= `</div>`

    $("#rpta_multiconsulta").html(tablaResult)
}


export default peticiones
import filters from '@/globalResources/lists/filters'
import errors from '@/globalResources/errors'

const peticiones = {}

peticiones.cargaCompletaUsuarios = function cargaCompletaUsuarios(orden,paginateSearch){
    
    //CONFIG LIST
    let envio = {}
    let resultEnvioUser = peticiones.LoadEnvioGeneral(orden)
    //console.log("este es el result send ha enviar:",resultEnvioUser)
    if(resultEnvioUser == false){
      return false;
    }
  
    envio = resultEnvioUser
  
    let route = `/administrador/usuarios/lista`
    let PersonalityPeticion = {
      'pageFilter':paginateSearch
    }
   
   
    //Previo al envio
    
      $("#result_iteraction_list").html(`<tr>
                                        <td colspan="6" class="text-center">
                                        <div id="carga_person">
                                                    <div class="loader">
                                                      Cargando
                                                    </div>
                                                  </div>
                                        </td></tr>`)
       
    //End previo al envio
    
    filters.loadListGeneral(envio,route,PersonalityPeticion,function(res){
      console.log("la data return list es: ",res)
      //Errores
        if(res.error == "failed"){
          //console.log("Error: ",res.errorThrown,res.jqXHR,res.textStatus) 
          mensajeCodigo = errors.codigos(res.jqXHR.status);
          $("#result_iteraction_list").html(`<tr>
                                    <td colspan="6" class="text-center">${mensajeCodigo}</td>
                                    </tr>`);
          return false;
        }
        if(res.error == true){
          let error_rpta = ``
          data.message.forEach(el => { 
            error_rpta += `${el} <br/>`
          })
          $("#result_iteraction_list").html(`<tr>
                                    <td colspan="6" class="text-center">${error_rpta}</td>
                                    </tr>`) 
          return false;
        }
          
      //Nulo
        let result = res.response
        if(result.data == null  || result.data.length == 0){
          $("#result_iteraction_list").html(`<tr>
              <td colspan="6" class="text-center">0 Resultados encontrados</td>
            </tr>`);
            return false;
        }
  
      //DATA CORRECTA
       
      let PersonalityTableList = {
        'SectionExcelExportFilter':false,
        'SectionInfoPageFilter':true,
        'SectionNumberPageFilter':true,
        'SectionFooterLinkNumberFilter':true
      }
      let printSections = {
        'SectionExcelExportFilter':$("#export-result-data"),
        'SectionInfoPageFilter':$("#details-result-data"),
        'SectionNumberPageFilter':$("#paginacion-result-data"),
        'SectionFooterLinkNumberFilter':$("#result_page_list")
      }
  
      //CARGA DE PARTES DE LIST FILTERS
      let pageR = result.meta.pagination
      filters.partialsTableList(pageR,PersonalityTableList,printSections)
  
      //END CARGA DE PARTES DE LIST FILTERS
  
      //CARGA DATA TABLA USUARIOS
      peticiones.cargalistUsuariosTable(res)
  
    })
    
}

peticiones.LoadEnvioGeneral = function LoadEnvioGeneral(orden)
{
  let envio = {}

  //ENVIOS
  if(orden.trim() != ''){
    envio.sort = orden
  } 
  //PAGINACION CANTIDAD LIST
  let paginacion = $("#paginateData").val()
  if(paginacion){
    
    let page = paginacion.toString()
    if(page.trim() != ''){ 
        if(isNaN(page)){
          $("#body-errors-modal").html("ingrese un numero válido, en un rango de [15-50]")
          $('#errorsModal').modal('show') 
          return false;
        }
        if( parseInt(page) < 15 || parseInt(page) > 100){
          $("#body-errors-modal").html("ingrese un rango de filtro de [15-50]")
          $('#errorsModal').modal('show') 
          return false;
        }
        envio.paginate = paginacion
    }
  }

  //ENVIO INPUT TEXT

  let nombreSearch = $("#nombre").val() 
  let apellidoSearch = $("#apellidos").val() 
  let documentoSearch = $("#documento").val() 
  let usuarioSearch = $("#usuario").val() 
  let validaTextoSearch = /[a-zA-Z]/;
  let validaNumbersSearch = /[0-9]/;
       
  if(nombreSearch.toString().trim() != ''){
       if(!validaTextoSearch.test(nombreSearch)){
          $("#body-errors-modal").html("El campo nombre debe tener almenos una letra")
          $('#errorsModal').modal('show') 
          return false;
      } 
    envio.nombre = nombreSearch
  }
  if(apellidoSearch.toString().trim() != ''){
       if(!validaTextoSearch.test(apellidoSearch)){
          $("#body-errors-modal").html("El campo apellidos debe tener almenos una letra")
          $('#errorsModal').modal('show') 
          return false;
      } 
    envio.apellidos = apellidoSearch
  }
  if(documentoSearch.toString().trim() != ''){
       if(!validaNumbersSearch.test(documentoSearch)){
          $("#body-errors-modal").html("El campo documento debe ser en formato números")
          $('#errorsModal').modal('show') 
          return false;
      } 
    envio.documento = documentoSearch
  }
  if(usuarioSearch.toString().trim() != ''){
       if(!validaTextoSearch.test(usuarioSearch)){
          $("#body-errors-modal").html("El campo usuario debe tener almenos una letra")
          $('#errorsModal').modal('show') 
          return false;
      } 
    envio.usuario = usuarioSearch
  }
   

  //END ENVIO TEXT INPUT

  return envio
}

peticiones.cargalistUsuariosTable = function cargalistUsuariosTable(res){
  
    let DataCollection = res.response.data
    let pageR = res.response.meta.pagination
  
    let count = parseInt(parseInt(pageR.current_page) - parseInt(1) ) * parseInt(pageR.per_page) + parseInt(1)
    //let from = count
    let permisos = res.permisos
    let tabla =``

    DataCollection.forEach(el => { 
      tabla += `<tr>`
      tabla += `<td class="text-center"> ${count++}</td>`
      tabla += `<td class="text-center"> ${el.nombre}</td>`
      tabla += `<td class="text-center"> ${el.apellidos}</td>`
      tabla += `<td class="text-center"> ${el.usuario}</td>`
      tabla += `<td class="text-center"> ${el.documento}</td>`

      if(permisos != null){
        tabla += `<td class="text-center">`
        if (permisos.show) {
          tabla += `
              <a href="/administrador/usuarios/create" class="btn btn-outline-primary btn-sm shadow-sm p-1 accionUsuarioShow"><i class="fa fa-eye icon-accion"></i></a>
          `
        }
        if (permisos.edit) {
          tabla += `
              <a href="/administrador/usuarios/${el.identificador}/show" class="btn btn-outline-success btn-sm shadow-sm p-1 accionUsuarioEdit" ><i class="fa fa-pencil icon-accion"></i></a>
          `
        }
        if (permisos.delete) {
          tabla += `
              <a href="javascript:void(0)" class="btn btn-outline-danger btn-sm shadow-sm p-1 accionUsuarioDelete"  data-id="${el.identificador}"><i class="fa fa-trash icon-accion"></i></a>
          `
        }
        tabla += `</td>`
      }
      
      tabla += `<tr>`
    });

    $("#result_iteraction_list").html(tabla)
 
}

export default peticiones
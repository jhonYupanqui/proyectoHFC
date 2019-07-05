import errors from  "@/globalResources/errors.js"
import peticiones from './peticiones.js'

$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

     //Busqueda Multiconsulta
     $("#form_multiconsulta #search_m").click(function(){
        
        //alert("aqui buscando");
        //active_desactive_form_search()
        buscar();
       
    })

    
    $("body").on("click",'#tablaresvarios tbody tr', function(event){
        event.preventDefault();  
        console.log("Aqui el click de multiples")
     
     // active_desactive_form_search()
        var $td= $(this).closest('tr').children('td');  

        var macaddress= $td.eq(0).text(); 	
        console.log("el mac addres es: "+macaddress);
        $("#text_m").val(macaddress);
        $("#type_m").val(2);
        $("#searchModal").modal('hide')//oculta modal
      
      burcarPorMacAddress(2,macaddress)
         
    })
})

function buscar()
{
    let type_data = $("#form_multiconsulta #type_m").val()
    let text =  $("#form_multiconsulta #text_m").val()

    peticiones.searchCountMulticonsulta(type_data,text,function(res){
      console.log("aqui luego del callbak")
        console.log("la data return count multiconsulta es: ",res)
       
        //Errores
          if(res.error == "failed"){
             console.log("Error: ",res.errorThrown,res.jqXHR,res.textStatus) 
            $("#rpta_multiconsulta").html(`${res.jqXHR.responseText}`); 

            if(res.jqXHR.responseJSON){
              if(res.jqXHR.responseJSON.mensaje){
                  let erroresMensaje = res.jqXHR.responseJSON.mensaje  //captura objeto
                  let mensaje = errors.mensajeErrorJson(erroresMensaje)
                  $("#rpta_multiconsulta").html(mensaje)
                  
              } 
            }
            if(res.jqXHR.status){
                let mensaje = errors.codigos(res.jqXHR.status)
                $("#body-errors-modal").html(mensaje)
                $('#errorsModal').modal('show')
                
            }
            
            return false;
          }
            /*if(res.error == true){
             
           let error_rpta = ``
            data.message.forEach(el => { 
              error_rpta += `${el} <br/>`
            })
            $("#rpta_multiconsulta").html(`${mensaje}`) 
            return false;
          }*/
            
        //Nulo
          let data = res.response
          if(data.cantidad  == 0){
            $("#rpta_multiconsulta").html(`0 Clientes Encontrados`);
              return false;
          } 
    
        //DATA CORRECTA

        //validando cantidad de resultado
        let cantidadResultado = data.cantidad
       

        if (cantidadResultado > 1) {
            console.log("cuentas con muchos resultados,saldra popup"); 
            //Armando el resultado
            let resultadoArmado = JSON.parse(data.resultado)
            peticiones.armandoMultiplesResultados(resultadoArmado)

            return false
        } 

        console.log("no tiene para modales: ",data.resultado)

        $("#rpta_multiconsulta").html(data.resultado);
    
      })
}

function burcarPorMacAddress(type_data,macaddress)
{ 
  peticiones.searchMulticonsultaByMacAddress(type_data,macaddress,function(res){
    console.log("aqui luego del callbak")
      console.log("la data return del macaddress: ",res)
     
      //Errores
          if(res.error == "failed"){
              console.log("Error: ",res.errorThrown,res.jqXHR,res.textStatus) 
            $("#rpta_multiconsulta").html(`${res.jqXHR.responseText}`); 

            if(res.jqXHR.responseJSON){
              if(res.jqXHR.responseJSON.mensaje){
                  let erroresMensaje = res.jqXHR.responseJSON.mensaje  //captura objeto
                  let mensaje = errors.mensajeErrorJson(erroresMensaje)
                  $("#rpta_multiconsulta").html(mensaje)
                  
              } 
            }
            if(res.jqXHR.status){
                let mensaje = errors.codigos(res.jqXHR.status)
                $("#body-errors-modal").html(mensaje)
                $('#errorsModal').modal('show')
                
            }
            
            return false;
          }
         
          
          //Nulo
          let data = res.response
          if(data.cantidad  == 0){
            $("#rpta_multiconsulta").html(`0 Clientes Encontrados`);
              return false;
          } 
  
        //DATA CORRECTA
        $("#rpta_multiconsulta").html(data.resultado);
        
    })

}
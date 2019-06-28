import errors from  "@/globalResources/errors.js"
$(function(){

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
   
    $('body').on("click",".accionUsuarioDelete",function(){
      let userIdSelect = $(this).data('id')
       
      console.log("el id ha eliminar es:",userIdSelect)
      let _this = $(this)
      var opcionDelete = confirm("¿Está seguro de eliminar al usuario?, ¡confirme nuevamente por favor!.");
      if (!opcionDelete) {
          return false
        }  
    
      if(userIdSelect){
        $.ajax({
          url:`/administrador/usuario/${userIdSelect}/eliminar`,
          method:"post",
          data:{},
          dataType: "json", 
        })
        .done(function(data){
          //console.log(data)
          if(data.error){
            $("#body-errors-modal").html("no se puedo eliminar al usuario, intente nuevamente.")
            $('#errorsModal').modal('show') 
            return false
          }
     
          $("#body-success-modal").html("Se eliminó al usuario seleccionado correctamente!.")
          $("#successModal").modal("show")
  
          _this.closest('tr').remove();
    
        })
        .fail(function(jqXHR, textStatus){
          console.log("error",jqXHR, textStatus)
          // $("#body-errors-modal").html(jqXHR.responseText)
          if(jqXHR.responseJSON){
            if(jqXHR.responseJSON.mensaje){
                  let erroresMensaje = jqXHR.responseJSON.mensaje  //captura objeto
                  let mensaje = errors.mensajeErrorJson(erroresMensaje)
                  $("#body-errors-modal").html(mensaje)
                  $('#errorsModal').modal('show') 
                  return false
              } 
          }
          if(jqXHR.status){
              let mensaje = errors.codigos(jqXHR.status)
              $("#body-errors-modal").html(mensaje)
              $('#errorsModal').modal('show')
              return false
          }

          $("#body-errors-modal").html("Se generó un problema inesperado, intente nuevamente.")
          $('#errorsModal').modal('show') 
        })
      }else{
          $("#body-errors-modal").html("No se puede encontrar al usuario, recargue la página e intentelo de nuevo.")
          $('#errorsModal').modal('show') 
      }
      
   
    })
  
  })
const errors = {}

errors.codigos = function codigos(codigo){

    let texto = ``
    switch (codigo) {
        case 403: 
          texto = `Petición no autoriazada.`
          break;
        case 404:
          texto = `Petición no encontrada.`
          break;
        case 405:
          texto = `Error en el servicio. Intente nuevamente.`
          break;
        case 409:
          texto = `Conflicto de petición en el servidor. Intente nuevamente.`
          break;
        case 500:
          texto = `Falla inesperada. Intente nuevamente.`
          break;  
        default:
            texto = `Falla inesperada con la petición. Intente nuevamente.`
          break;
      }

      return texto

}

errors.mensajeErrorJson = function mensajeErrorJson(erroresJson){
 
          console.log("el tipo de mensaje es:",typeof(erroresJson))
          if (typeof(erroresJson) == "string") { 
              return erroresJson
          }
          //recorreo objeto como array
          let msj = ``
          Object.keys(erroresJson).forEach(key =>{
              msj +=`${key} : ${erroresJson[key]} <br/>`
              
          }) 
          return msj; 
}

export default errors
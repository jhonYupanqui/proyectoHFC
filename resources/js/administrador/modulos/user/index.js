import peticiones from './peticiones.js'
  
 $(function(){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

     //peticiones.cargaCompletaUsuarios(SORTBY,0)
 
         $('#listUsersPrint').DataTable({
            "serverSide": true,
            "ajax": "/administrador/usuarios/lista-ajax",
            "columns": [
                {data: 'id'},
                {data: 'nombre'},
                {data: 'apellidos'},
                {data: 'username'},
                {data: 'email'},
                {data: 'btn'},
            ],
            "language": {
                "info": "_TOTAL_ registros",
                "search": "Buscar",
                "paginate": {
                    "next": "Siguiente",
                    "previous": "Anterior",
                },
                "lengthMenu": 'Mostrar <select >'+
                            '<option value="15">15</option>'+
                            '<option value="50">50</option>'+
                            '<option value="100">100</option>'+
                            '<option value="-1">Todos</option>'+
                            '</select> registros',
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "emptyTable": "No hay datos",
                "zeroRecords": "No hay coincidencias", 
                "infoEmpty": "",
                "infoFiltered": ""
            }
        });

        $("#listUsersPrint").parent().addClass("table-responsive tableFixHead") 
     
  /**Tabla fixed */
  var th = $('.tableFixHead').find('thead th')
 
  $('.tableFixHead').on('scroll', function() {
      //console.log("ejecutando"+this.scrollTop); 
      th.css('transform', 'translateY('+ this.scrollTop +'px)'); 
  });
  /**Tabla tabla fixed */
   
 })

 
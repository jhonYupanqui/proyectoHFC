
  //limpiadores
const limpia = {}
    function limpiaTexInput()
    {
      $(".validateText").val('')
    }

    function limpiaSelectInput()
    {
      if ($(".validateSelect")[0]) {
        $(".validateSelect")[0].selectedIndex = 0
      }
     
    }

    function limpiaCheckboxInput()
    {
      $('.validateCheckbox').prop('checked',false);
      $('.validateCheckbox').prop('disabled',false);
    }

    function limpiaFilesInput()
    {
      //file input bootstrap4
      $(".validateFile").fileinput('clear');
    }

    limpia.defaultImageReset = function defaultImageReset(data,url)
    {
      data.attr('src',url);
    }

    limpia.limpiaHtml = function limpiaHtml(html)
    {
      html.html('')
    }

    limpia.limpiaFormUser = function limpiaFormUser()
    {
      limpiaTexInput()
      limpiaSelectInput()
      limpiaCheckboxInput()
    }

    limpia.limpiaFormRol = function limpiaFormRol()
    {
      limpiaTexInput()
      limpiaSelectInput()
      limpiaCheckboxInput()
    }

    limpia.limpiaFormModulos = function limpiaFormModulos()
    {
      limpiaTexInput()
      limpiaSelectInput()
      limpiaFilesInput()
    }
    limpia.limpiaFormCursos = function limpiaFormCursos()
    {
      limpiaTexInput()
      limpiaFilesInput()
    }
    limpia.limpiaFormGrados = function limpiaFormGrados()
    {
      limpiaTexInput()
    }
    limpia.limpiaFormNiveles = function limpiaFormNiveles()
    {
      limpiaTexInput() 
    }
    limpia.limpiaFormYears = function limpiaFormYears()
    {
      limpiaTexInput() 
    }
    limpia.limpiaFormPeriodos = function limpiaFormPeriodos()
    {
      limpiaTexInput()
      limpiaSelectInput()
    }
    limpia.limpiaFormPensiones = function limpiaFormPensiones()
    {
      limpiaTexInput()
    }
    limpia.limpiaFormDetallePension = function limpiaFormDetallePension()
    {
      limpiaTexInput()
    }
    limpia.limpiaFormHorarios = function limpiaFormHorarios()
    {
      limpiaTexInput()
    }
    limpia.limpiaFormAsignacionProfesores = function limpiaFormAsignacionProfesores()
    {
      limpiaTexInput()
    }
    limpia.limpiaFormDetalleMatricula = function limpiaFormDetalleMatricula()
    {
      limpiaTexInput()
    }

    export default limpia

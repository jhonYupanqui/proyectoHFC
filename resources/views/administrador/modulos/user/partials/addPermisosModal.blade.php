<div class="modal fade" id="addPermisosModal" tabindex="-1" role="dialog" aria-labelledby="addPermisosModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header px-2 py-1">
        <h5 class="modal-title" id="exampleModalLongTitle">Agregar nuevos permisos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body modal-height-responsive">
        <div class="row">
          <div class="col-12 errors" id="rpta_store_checked_permisos">

          </div>
          <div class="col-12" id="modulosAndPermisosList">
           
              {{-- Collapse Module and Permisos--}}
                  <div class="accordion" id="accordionModulosUserStore">
                      @php
                          $modulos_permitidos = $modulos_permisos->where('tipo','Modulo')->all();
                      @endphp
                      @foreach ($modulos_permitidos as $modulos)
                              @php
                                  $modulo = $modulos;
                                  $id_modulo = $modulo->id;
                                  $submodulos = $modulos_permisos->filter(function ($value) use ($id_modulo) {
                                          if($value->referencia == $id_modulo) return $value;
                                  }); 
                              @endphp
                              <div class="card">
                                  <div class="card-header p-0" id="{{$modulo->nombre}}store">
                                      <h5 class="mb-0">
                                      <button class="btn btn-link btn-sm collapsed w-100 text-left font-weight-bold" type="button" data-toggle="collapse" data-target="#modul{{$modulo->id}}" aria-expanded="true" aria-controls="modul{{$modulo->id}}">
                                          <i class="fa fa-angle-double-right"></i> {{$modulo->nombre}}
                                      </button>
                                      </h5>
                                  </div>
                              </div>
                              <div id="modul{{$modulo->id}}" class="collapse" aria-labelledby="{{$modulo->nombre}}store" data-parent="#accordionModulosUserStore">
                                  <div class="card-body font-italic p-1">

                                    @if(isset($submodulos))
                                      @if(count($submodulos)> 0)

                                        <label class=" form-control-sm b-0">
                                          <input type="checkbox" name="permissions[]" class="validateCheckbox" value="{{$modulo->id}}" id="checkstore{{$modulo->id}}">
                                            <span>{{$modulo->nombre}}</span>
                                        </label>

                                        @foreach ($submodulos as $submodulo)
                                          <label class=" form-control-sm b-0">
                                              <input type="checkbox" name="permissions[]" class="validateCheckbox" value="{{$submodulo->id}}" id="checkstore{{$submodulo->id}}">
                                                <span>{{$submodulo->nombre}}</span>
                                            </label>
                                        @endforeach

                                      @endif
                                    @endif

                                  </div>
                              </div>
                      @endforeach
                  </div>
              {{-- Fin Module and permisos--}}
          </div> 
        </div>
      </div> 
    </div>
  </div>
</div>
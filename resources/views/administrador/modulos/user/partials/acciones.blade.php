@if (Auth::user()->HasPermiso('submodulo.usuario.show'))
    <a href="{{ route('submodulo.usuario.show', $id) }}"  class="btn btn-outline-primary btn-sm shadow-sm p-1 accionUsuarioShow"><i class="fa fa-eye icon-accion"></i></a>
@endif
@if (Auth::user()->HasPermiso('submodulo.usuario.edit'))
    <a href="{{ route('submodulo.usuario.edit', $id) }}" class="btn btn-outline-success btn-sm shadow-sm p-1 accionUsuarioEdit" ><i class="fa fa-pencil icon-accion"></i></a>
@endif
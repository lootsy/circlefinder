@if(!is_trash_route())
<div class="crud-navbar pt-2 pb-4">
    <a href="{{ route($route_prefix . 'create') }}" class="btn btn-success">New</a>
    <a href="{{ route($route_prefix . 'trash') }}" class="btn btn-light">Trash</a>
</div>
@endif
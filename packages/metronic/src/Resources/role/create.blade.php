@extends('isotope::master')

@section('title', 'Role Create')

@push('buttons')
<a href="{{ route(tenant() ? 'authorization.roles.index' : 'owner.authorization.roles.index') }}" class="btn btn-sm btn-isotope fw-bold">List</a>
<button type="submit" form="role-form" class="btn btn-sm btn-isotope fw-bold ms-1">
    <i class="fas fa-paper-plane"></i>
    Save
</button>
@endpush

@section('content')
<div class="col-12 mb-2">
    <div class="row">
            <div class="col-12 mb-2">
            <div class="form-check form-switch mb-2">
                <input type="checkbox" id="select-all-checkbox" class="form-check-input">
                <label class="form-check-label fw-bold ms-2" for="select-all-checkbox">Select All</label>
            </div>
        </div>
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <form action="{{ route(tenant() ? 'authorization.roles.store' : 'owner.authorization.roles.store') }}" method="post" id="role-form">
                            @csrf
                        </form>
                        <div class="col-4 mb-2">
                            <label for="">Role Name: </label>
                            <input name="title" form="role-form" type="text" class="form-control form-control-sm"
                                placeholder="ex. Sales Manager" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @foreach ($routes as $module => $controllers)
        @php
            $moduleId = Str::slug($module);
        @endphp
        <div class="col-12 mt-5 mb-3">
            <div class="d-flex align-items-center cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $moduleId }}">
                <div class="flex-grow-1 border-top border-gray-300"></div>
                <h4 class="mx-4 text-gray-700 fw-bolder text-uppercase tracking-widest d-flex align-items-center">
                    {{ $module }}
                    <i class="fas fa-chevron-down ms-2 fs-8 transition-all {{ $loop->first ? '' : 'rotate-180' }}"></i>
                </h4>
                <div class="flex-grow-1 border-top border-gray-300"></div>
            </div>
        </div>
        <div class="col-12 collapse {{ $loop->first ? 'show' : '' }}" id="collapse-{{ $moduleId }}">
            <div class="row">
                @foreach ($controllers as $controller => $permission)
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="pt-2 ps-5 min-h-50px bg-light-primary rounded-top">
                            <div class="card-title">
                                <h3 class="card-label fs-6 fw-bold">
                                    <div class="row align-items-center">
                                        <div class="col-9">
                                            {{ $controller }}
                                        </div>
                                        <div class="col-3">
                                            <div class="form-check form-switch form-check-custom form-check-solid d-inline-block pe-3">
                                                <input form="role-form" type="checkbox"
                                                    class="form-check-input h-20px w-30px card-checkbox">
                                            </div>
                                        </div>
                                    </div>
                                </h3>
                            </div>
                        </div>
                        <div class="card-body p-5">
                            <div class="row">
                                @foreach ($permission as $route)
                                <div class="col-12 mb-2">
                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                        <input form="role-form" type="checkbox" class="form-check-input h-20px w-30px permission-checkbox"
                                            id="{{ $route['permission'] }}" name="permissions[]"
                                            value="{{ json_encode($route) }}" {{ old($route['permission'])? 'checked' : '' }}>
                                        <label class="form-check-label fs-7 fw-semibold ms-2" for="{{ $route['permission'] }}">{{ $route['title']
                                            }}</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('css')
<style>
    .card-checkbox {
        position: absolute;
        right: 0;
        margin-right: 10px
    }
    .rotate-180 {
        transform: rotate(180deg);
    }
    .transition-all {
        transition: all 0.3s ease-in-out;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>
@endpush

@push('js')
<script>
    $(document).ready(function(){
        $(document).on('click','.card-checkbox',checkAllOrNot);

        // Select All functionality
        $('#select-all-checkbox').on('change', function() {
            let checked = $(this).is(':checked');
            $('.permission-checkbox').prop('checked', checked);
            $('.card-checkbox').prop('checked', checked);
        });

        // If any permission-checkbox is unchecked, uncheck select-all
        $(document).on('change', '.permission-checkbox', function() {
            if(!$(this).is(':checked')) {
                $('#select-all-checkbox').prop('checked', false);
            } else if ($('.permission-checkbox:checked').length === $('.permission-checkbox').length) {
                $('#select-all-checkbox').prop('checked', true);
            }
        });

        // Toggle rotation for collapse icons
        $('.collapse').on('show.bs.collapse', function() {
            $(this).prev().find('.fa-chevron-down').removeClass('rotate-180');
        }).on('hide.bs.collapse', function() {
            $(this).prev().find('.fa-chevron-down').addClass('rotate-180');
        });
    });

    function checkAllOrNot()
    {   
        let permission_checkboxs = $(this).closest('.card').find('.permission-checkbox');
        if($(this).is(':checked'))
            permission_checkboxs.prop('checked',true);
        else
            permission_checkboxs.prop('checked',false);

        // Update select-all-checkbox state
        if ($('.permission-checkbox:checked').length === $('.permission-checkbox').length) {
            $('#select-all-checkbox').prop('checked', true);
        } else {
            $('#select-all-checkbox').prop('checked', false);
        }
    }
</script>
@endpush

@endsection
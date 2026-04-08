@if(session('message'))
    <script> toastr.info(`{!! session("message") !!}`)</script>
@elseif(session()->has('success'))
    <script> toastr.success(`{!! session("success") !!}`)</script>
@elseif(session()->has('error'))
    <script>
        Swal.fire({
            title: @json(session("error")),
            icon : "error",
        });
    </script>
@elseif($errors->any())
    @php
        $error_msg = '';
    @endphp
    @foreach ($errors->all() as $error)
        @php
            $error_msg = "<span>{$error}</span>";
        @endphp
    @endforeach
    <script>
        Swal.fire({
            title: @json($error_msg),
            icon : "error",
        });
    </script>
@endif

@if (count($errors) > 0)
    <div class="alert alert-danger alert-highlighted" role="alert">
        <i class="mdi mdi-alert-circle"></i><strong>اخطار!</strong><br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-highlighted" role="alert">
        <i class="mdi mdi-check-decagram"></i>
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-highlighted" role="alert">
        <i class="mdi mdi-alert-circle"></i>
        {{ session('error') }}
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-highlighted" role="alert">
        <i class="mdi mdi-alert"></i>
        {{ session('warning') }}
    </div>
@endif

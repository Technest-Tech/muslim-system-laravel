@extends('layouts.index')

@section('content')
    <div class="page-content-wrapper border">

        <!-- Title -->
        @if(auth()->user()->user_type == \App\Models\User::USER_TYPE['admin'])
        <div class="row">
            <div class="col-12 mb-3">
                <h1 class="h3 mb-2 mb-sm-0 text-end">احصائيات هذا الشهر</h1>
            </div>
        </div>
        @endif

        @if(auth()->user()->user_type == \App\Models\User::USER_TYPE['admin'])
            <!-- Counter boxes START -->
            <div class="row g-4 mb-4">
                <!-- Counter item -->
                <div class="col-md-6 col-xxl-3">
                    <div class="card card-body bg-warning bg-opacity-15 p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Digit -->
                            <div>
                                <h2 class="mb-0 fw-bold">{{\App\Models\Billings::whereMonth('created_at',\Carbon\Carbon::now()->month)->where('currency','USD')->sum('amount')}}</h2>
                                <span class="mb-0 h5 fw-light">دولار</span>
                            </div>
                            <!-- Icon -->
                            <div class="icon-lg rounded-circle bg-warning text-white mb-0"><i class="fas fa-dollar-sign"></i></div>
                        </div>
                    </div>
                </div>

                <!-- Counter item -->
                <div class="col-md-6 col-xxl-3">
                    <div class="card card-body bg-purple bg-opacity-10 p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Digit -->
                            <div>
                                <h2 class=" mb-0 fw-bold">{{\App\Models\Billings::whereMonth('created_at',\Carbon\Carbon::now()->month)->where('currency','GBP')->sum('amount')}}</h2>
                                <span class="mb-0 h5 fw-light">جنيه استرليني</span>
                            </div>
                            <!-- Icon -->
                            <div class="icon-lg rounded-circle bg-purple text-white mb-0"><i class="fas fa-pound-sign"></i></div>
                        </div>
                    </div>
                </div>

                <!-- Counter item -->
                <div class="col-md-6 col-xxl-3">
                    <div class="card card-body bg-primary bg-opacity-10 p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Digit -->
                            <div>
                                <h2 class="mb-0 fw-bold">{{\App\Models\Billings::whereMonth('created_at',\Carbon\Carbon::now()->month)->where('currency','EUR')->sum('amount')}}</h2>
                                <span class="mb-0 h5 fw-light">يورو</span>
                            </div>
                            <!-- Icon -->
                            <div class="icon-lg rounded-circle bg-primary text-white mb-0"><i class="fas fa-euro-sign"></i></div>
                        </div>
                    </div>
                </div>
                <!-- Counter item -->
                <div class="col-md-6 col-xxl-3">
                    <div class="card card-body bg-primary bg-opacity-10 p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Digit -->
                            <div>
                                <h2 class="mb-0 fw-bold">{{\App\Models\Billings::whereMonth('created_at',\Carbon\Carbon::now()->month)->where('currency','NZD')->sum('amount')}}</h2>
                                <span class="mb-0 h5 fw-light">دولار نيوزلندي</span>
                            </div>
                            <!-- Icon -->
                            <div class="icon-lg rounded-circle bg-primary text-white mb-0"><i class="fas fa-euro-sign"></i></div>
                        </div>
                    </div>
                </div>
                <!-- Counter item -->
                <div class="col-md-6 col-xxl-3">
                    <div class="card card-body bg-primary bg-opacity-10 p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Digit -->
                            <div>
                                <h2 class="mb-0 fw-bold">{{\App\Models\Billings::whereMonth('created_at',\Carbon\Carbon::now()->month)->where('currency','CAD')->sum('amount')}}</h2>
                                <span class="mb-0 h5 fw-light">دولار كندي</span>
                            </div>
                            <!-- Icon -->
                            <div class="icon-lg rounded-circle bg-primary text-white mb-0"><i class="fas fa-euro-sign"></i></div>
                        </div>
                    </div>
                </div>

                <!-- Counter item -->
                <div class="col-md-6 col-xxl-3">
                    <div class="card card-body bg-success bg-opacity-10 p-4 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- Digit -->
                            <div>
                                <div class="d-flex">
                                    <h2 class=" mb-0 fw-bold">{{\App\Models\User::where('user_type',\App\Models\User::USER_TYPE['teacher'])->count()}}</h2>
                                </div>
                                <span class="mb-0 h5 fw-light">عدد المعلمين</span>
                            </div>
                            <!-- Icon -->
                            <div class="icon-lg rounded-circle bg-success text-white mb-0"><i class="fas fa-users"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Counter boxes END -->
            <!-- Counter item -->
            <div class="col-md-6 col-xxl-3">
                <div class="card card-body bg-success bg-opacity-10 p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Digit -->
                        <div>
                            <div class="d-flex">
                                <h2 class=" mb-0 fw-bold">{{\App\Models\User::where('user_type',\App\Models\User::USER_TYPE['student'])->count()}}</h2>
                            </div>
                            <span class="mb-0 h5 fw-light">عدد الطلاب</span>
                        </div>
                        <!-- Icon -->
                        <div class="icon-lg rounded-circle bg-success text-white mb-0"><i class="fas fa-graduation-cap"></i></div>
                    </div>
                </div>
            </div>

            <!-- Change Password Form START -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-body">
                        <h4 class="card-title">تغيير كلمة المرور</h4>
                        <form method="POST" action="{{ route('password.change') }}">
                            @csrf

                            <!-- Display Validation Errors -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="current_password" class="form-label">كلمة المرور الحالية</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                                @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">كلمة المرور الجديدة</label>
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" required>
                                @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                                <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" id="new_password_confirmation" name="new_password_confirmation" required>
                                @error('new_password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">تغيير كلمة المرور</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Change Password Form END -->
        @elseif(auth()->user()->user_type == \App\Models\User::USER_TYPE['teacher'])
            <!-- Counter boxes START -->
            <div class="d-flex align-items-center justify-content-center">
                <img src="{{asset('muslim.png')}}" style="width: 50%;height: 50%">
            </div>
        @endif
    </div>
    <!-- Counter boxes END -->



@endsection

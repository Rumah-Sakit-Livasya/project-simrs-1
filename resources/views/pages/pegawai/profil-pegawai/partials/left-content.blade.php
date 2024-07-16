@section('extended-css')
    <style>
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* Warna overlay (hitam dengan opacity 0.5) */
            opacity: 0;
            /* Awalnya tidak terlihat */
            transition: opacity 0.3s ease;
            /* Efek transisi untuk opacity */
            border-radius: 100px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .profil:hover .overlay {
            opacity: 1;
            /* Saat gambar digulirkan, overlay akan terlihat dengan opacity 1 */
        }

        .edit-icon {
            color: white;
            /* Warna ikon */
            font-size: 24px;
            /* Ukuran ikon */
        }

        .profile-image-wrapper {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            /* Untuk membuat gambar bundar */
            overflow: hidden;
            /* Untuk memastikan gambar tidak keluar dari lingkaran */
        }

        .profile-image {
            width: 100%;
            height: 100%;
            background-size: cover;
            /* Untuk mengisi seluruh area dengan gambar */
            background-position: center;
            /* Untuk memastikan gambar selalu berada di tengah */
            cursor: pointer;
        }
    </style>
@endsection
<div class="col-lg-3">
    <div class="card mb-g rounded-top">
        <div class="row no-gutters row-grid">
            <div class="col-12">
                <div class="d-flex flex-column align-items-center justify-content-center p-4" id="ubah-profil"
                    data-id="{{ auth()->user()->employee->id }}">
                    <div class="position-relative profil">
                        <!-- Div overlay yang akan muncul saat gambar digulirkan -->
                        <div class="overlay">
                            <!-- Icon edit -->
                            <i class="fas fa-pencil-alt edit-icon"></i>
                        </div>
                        <!-- Gambar profil -->
                        @if (Storage::exists('public/employee/profile/' . auth()->user()->employee->foto))
                            <img src="{{ asset('storage/employee/profile/' . auth()->user()->employee->foto) }}"
                                class="rounded-circle shadow-2 img-thumbnail" alt=""
                                style="width: 100px; height: 100px; object-fit: cover; cursor: pointer;">
                        @else
                            <img src="{{ auth()->user()->employee->gender == 'Laki-laki' ? '/img/demo/avatars/avatar-c.png' : '/img/demo/avatars/avatar-p.png' }}"
                                class="rounded-circle shadow-2 img-thumbnail" alt=""
                                style="width: 100px; height: 100px; object-fit: cover; cursor: pointer;">
                        @endif
                    </div>
                    <h5 class="mb-0 fw-700 text-center mt-3">
                        {{ auth()->user()->name }}
                        <small class="text-muted mb-0">{{ $employee->organization->name }}</small>
                    </h5>
                    <div class="mt-4 text-center demo">
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="accordion accordion-hover" id="info-parent">

                    <div class="card">
                        <div class="card-header">
                            <a href="javascript:void(0);" class="card-title" data-toggle="collapse"
                                data-target="#general-info" aria-expanded="true">
                                <i class="fal fa-user-alt width-2 fs-xl"></i>
                                General
                                <span class="ml-auto">
                                    <span class="collapsed-reveal">
                                        <i class="fal fa-chevron-up fs-xl"></i>
                                    </span>
                                    <span class="collapsed-hidden">
                                        <i class="fal fa-chevron-down fs-xl"></i>
                                    </span>
                                </span>
                            </a>
                        </div>
                        <div id="general-info" class="collapse show" data-parent="#info-parent">
                            <div class="card-body p-0">
                                <div class="nav flex-column row row-grid no-gutters" id="v-pills-tab" role="tablist"
                                    aria-orientation="vertical">
                                    <a class="nav-link active col-12 p-3" id="v-pills-personal-tab" data-toggle="pill"
                                        href="#v-pills-personal" role="tab" aria-controls="v-pills-personal">
                                        <span class="ml-1">Info Personal</span>
                                    </a>
                                    <a class="nav-link col-12 p-3" id="v-pills-pekerjaan-tab" data-toggle="pill"
                                        href="#v-pills-pekerjaan" role="tab" aria-controls="v-pills-pekerjaan">
                                        <span class="ml-1">Info Pekerjaan</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <a href="javascript:void(0);" class="card-title collapsed" data-toggle="collapse"
                                data-target="#time-management-info" aria-expanded="false">
                                <i class="fal fa-clock width-2 fs-xl"></i>
                                Manajemen Waktu
                                <span class="ml-auto">
                                    <span class="collapsed-reveal">
                                        <i class="fal fa-chevron-up fs-xl"></i>
                                    </span>
                                    <span class="collapsed-hidden">
                                        <i class="fal fa-chevron-down fs-xl"></i>
                                    </span>
                                </span>
                            </a>
                        </div>
                        <div id="time-management-info" class="collapse" data-parent="#info-parent">
                            <div class="card-body p-0">
                                <div class="nav flex-column row row-grid no-gutters" id="v-pills-tab" role="tablist"
                                    aria-orientation="vertical">
                                    <a class="nav-link col-12 p-3" id="v-pills-attendance-tab" data-toggle="pill"
                                        href="#v-pills-attendance" role="tab" aria-controls="v-pills-attendance">
                                        <span class="ml-1">Absensi</span>
                                    </a>
                                    <a class="nav-link col-12 p-3" id="v-pills-time-off-tab" data-toggle="pill"
                                        href="#v-pills-time-off" role="tab" aria-controls="v-pills-time-off">
                                        <span class="ml-1">Libur</span>
                                    </a>
                                    <a class="nav-link col-12 p-3" id="v-pills-overtime-tab" data-toggle="pill"
                                        href="#v-pills-overtime" role="tab" aria-controls="v-pills-overtime">
                                        <span class="ml-1">Lembur</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <a href="javascript:void(0);" class="card-title collapsed" data-toggle="collapse"
                                data-target="#js_demo_accordion-5c" aria-expanded="false">
                                <i class="fab fa-buromobelexperte width-2 fs-xl"></i>
                                Payroll
                                <span class="ml-auto">
                                    <span class="collapsed-reveal">
                                        <i class="fal fa-chevron-up fs-xl"></i>
                                    </span>
                                    <span class="collapsed-hidden">
                                        <i class="fal fa-chevron-down fs-xl"></i>
                                    </span>
                                </span>
                            </a>
                        </div>
                        <div id="js_demo_accordion-5c" class="collapse" data-parent="#js_demo_accordion-5">
                            <div class="card-body p-0">
                                <div class="col-12 p-3">
                                    <a href="#" class="d-block w-100">Payroll Info</a>
                                </div>
                                <div class="col-12 p-3">
                                    <a href="#" class="d-block w-100">Payslip</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <a href="javascript:void(0);" class="card-title collapsed" data-toggle="collapse"
                                data-target="#js_demo_accordion-5d" aria-expanded="false">
                                <i class="fal fa-money-bill-alt width-2 fs-xl"></i>
                                Finance
                                <span class="ml-auto">
                                    <span class="collapsed-reveal">
                                        <i class="fal fa-chevron-up fs-xl"></i>
                                    </span>
                                    <span class="collapsed-hidden">
                                        <i class="fal fa-chevron-down fs-xl"></i>
                                    </span>
                                </span>
                            </a>
                        </div>
                        <div id="js_demo_accordion-5d" class="collapse" data-parent="#js_demo_accordion-5">
                            <div class="card-body p-0">
                                <div class="col-12 p-3">
                                    <a href="#" class="d-block w-100">Reimbursement</a>
                                </div>
                                <div class="col-12 p-3">
                                    <a href="#" class="d-block w-100">Loan</a>
                                </div>
                                <div class="col-12 p-3">
                                    <a href="#" class="d-block w-100">Cash Advance</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

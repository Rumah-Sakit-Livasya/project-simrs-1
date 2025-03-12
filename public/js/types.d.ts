declare function showErrorAlert(message: string): void;
declare function showSuccessAlert(message: string): void;
declare function showErrorAlertNoRefresh(message: string): void;

interface Registration {
    id: number;
    date: string;
    patient_id: number;
    user_id: number;
    employee_id: number;
    penjamin_id: number;
    doctor_id: number;
    departement_id: number;
    registration_type: string;
    registration_date: string;
    registration_close_date: string | null;
    poliklinik: string;
    registration_number: string;
    diagnosa_awal: string;
    diagnosa_akhir: string | null;
    kartu_pasien: number;
    rujukan: string;
    no_urut: string;
    doctor_perujuk: string | null;
    tipe_rujukan: string | null;
    nama_perujuk: string | null;
    telp_perujuk: string | null;
    alamat_perujuk: string | null;
    diagnosa: string | null;
    status: string;
    tipe_order: string | null;
    order_lab: string | null;
    order_rad: string | null;
    kelas_rawat_id: string | null;
    paket: string | null;
    tipe_jadwal: string | null;
    igd_type: string;
    odc_type: string | null;
    pelayanan: string | null;
    kamar_tujuan: string | null;
    prosedur_masuk: string | null;
    titip_kelas_rawat: string | null;
    tipe_perawatan: string | null;
    tindakan: string | null;
    deleted_at: string | null;
    created_at: string;
    updated_at: string;
    penjamin: Penjamin;
    patient: Patient;
    doctor: Doctor;
    departement: Departement;
}

interface Penjamin {
    id: number;
    group_penjamin_id: number;
    mulai_kerjasama: string;
    akhir_kerjasama: string | null;
    tipe_perusahaan: string;
    kode_perusahaan: string | null;
    nama_perusahaan: string;
    alamat_surat: string | null;
    alamat_email: string | null;
    direktur: string | null;
    nama_kontak: string | null;
    diskon: string;
    jabatan: string | null;
    termasuk_penjamin: number;
    fax_kontak: string | null;
    alamat: string | null;
    alamat_tagihan: string | null;
    telepon_kontak: string | null;
    email_kontak: string | null;
    kota: string | null;
    status: number;
    kode_pos: string | null;
    jenis_kerjasama: string;
    jenis_kontrak: string;
    pasien_otc: number;
    keterangan: string | null;
    deleted_at: string | null;
    created_at: string | null;
    updated_at: string | null;
}

interface Patient {
    id: number;
    medical_record_number: string;
    family_id: number;
    penjamin_id: number;
    name: string;
    place: string;
    date_of_birth: string;
    nickname: string;
    title: string;
    gender: string;
    religion: string;
    blood_group: string;
    allergy: string | null;
    married_status: string | null;
    language: string;
    citizenship: string;
    id_card: string;
    address: string;
    ward: string;
    subdistrict: string;
    regency: string;
    province: string;
    mobile_phone_number: string;
    email: string | null;
    last_education: string;
    ethnic: string;
    job: string;
    nama_penjamin: string | null;
    nomor_penjamin: string;
    nama_pegawai: string;
    nama_perusahaan_pegawai: string;
    hubungan_pegawai: string;
    nomor_kepegawaian: string;
    bagian_pegawai: string;
    grup_perusahaan: string | null;
    deleted_at: string | null;
    created_at: string;
    updated_at: string;
}

interface Doctor {
    id: number;
    employee_id: number;
    departement_id: number;
    kode_dpjp: string | null;
    deleted_at: string | null;
    created_at: string;
    updated_at: string;
    employee: Employee;
    department_from_doctors: DepartmentFromDoctors;
}

interface Employee {
    id: number;
    company_id: number;
    organization_id: number;
    job_position_id: number;
    job_level_id: number;
    approval_line: number;
    approval_line_parent: string | null;
    employee_code: string;
    title: string | null;
    fullname: string;
    degree: string | null;
    email: string;
    mobile_phone: string;
    place_of_birth: string;
    birthdate: string;
    gender: string;
    marital_status: string;
    blood_type: string | null;
    religion: string;
    last_education: string | null;
    identity_type: string;
    identity_number: string;
    identity_expire_date: string;
    postal_code: string;
    citizen_id_address: string;
    residental_address: string;
    barcode: string | null;
    employment_status: string;
    join_date: string;
    end_status_date: string | null;
    resign_date: string | null;
    basic_salary: string;
    salary_type: string | null;
    payment_schedule: string;
    protate_setting: string | null;
    allowed_for_overtime: number;
    npwp: string | null;
    ptkp_status: string;
    tax_methode: string;
    tax_salary: string;
    taxable_date: string | null;
    employment_tax_status: string;
    beginning_netto: string | null;
    pph21_paid: string | null;
    bpjs_ker_number: string | null;
    npp_ker_bpjs: string | null;
    bpjs_ker_date: string | null;
    bpjs_kes_number: string | null;
    bpjs_kes_family: string | null;
    bpjs_kes_date: string | null;
    bpjs_kes_cost: string | null;
    jht_cost: string | null;
    jaminan_pensiun_cost: string | null;
    jaminan_pensiun_date: string | null;
    sip: string;
    expire_sip: string;
    foto: string | null;
    is_active: number;
    is_doctor: number;
    ttd: string | null;
    created_at: string;
    updated_at: string;
    shift_id: string | null;
}

interface DepartmentFromDoctors {
    id: number;
    name: string;
    kode: string;
    keterangan: string;
    quota: string | null;
    kode_poli: string | null;
    default_dokter: number;
    publish_online: string | null;
    revenue_and_cost_center: string | null;
    master_layanan_rl: string | null;
    deleted_at: string | null;
    created_at: string;
    updated_at: string;
}

interface Departement {
    id: number;
    name: string;
    kode: string;
    keterangan: string;
    quota: string | null;
    kode_poli: string | null;
    default_dokter: number;
    publish_online: string | null;
    revenue_and_cost_center: string | null;
    master_layanan_rl: string | null;
    deleted_at: string | null;
    created_at: string;
    updated_at: string;
}

interface ParameterRadiologi {
    id: number;
    grup_parameter_radiologi_id: number;
    kategori_radiologi_id: number;
    kode: number;
    parameter: string;
    is_reverse: boolean;
    is_kontras: boolean;
    deleted_at: string | null;
    created_at: string;
    updated_at: string;
}

interface TarifRadiologi {
    id: number;
    parameter_radiologi_id: number;
    group_penjamin_id: number;
    kelas_rawat_id: number;
    share_dr: number;
    share_rs: number;
    total: number;
    deleted_at: string | null;
    created_at: string;
    updated_at: string;
}

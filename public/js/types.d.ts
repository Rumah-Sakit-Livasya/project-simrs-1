declare function showErrorAlert(message: string): void;
declare function showSuccessAlert(message: string): void;
declare function showErrorAlertNoRefresh(message: string): void;

interface GroupPenjamin {
    id: number;
    name: string;
    code: string;
    deleted_at: string | null;
    created_at: string;
    updated_at: string;
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
    is_bpjs: boolean;
    keterangan: string | null;
    deleted_at: string | null;
    created_at: string | null;
    updated_at: string | null;
}

interface TarifLaboratorium {
    id: number;
    parameter_laboratorium_id: number;
    group_penjamin_id: number;
    kelas_rawat_id: number;
    share_dr: number;
    share_rs: number;
    prasarana: number;
    bhp: number;
    total: number;
    deleted_at: string | null;
    created_at: string;
    updated_at: string;
}

interface ParameterLaboratorium {
    id: number;
    grup_parameter_laboratorium_id: number;
    kategori_laboratorium_id: number;
    tipe_laboratorium_id: number;
    kode: number;
    parameter: string;
    satuan: string;
    status: number | null;
    is_hasil: number;
    is_order: number;
    tipe_hasil: string;
    metode: string;
    no_urut: number;
    sub_parameter: string | null;
    deleted_at: string | null;
    created_at: string;
    updated_at: string;
}

interface KategoriLaboratorium {
    id: number;
    nama_kategori: string;
    status: number;
    deleted_at: string | null;
    created_at: string;
    updated_at: string;
    parameter_laboratorium: ParameterLaboratorium[];
}

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
    penjamin?: Penjamin;
    patient?: Patient;
    doctor?: Doctor;
    departement?: Departement;
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
    employee?: Employee;
    department_from_doctors?: DepartmentFromDoctors;
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

interface KategoriRadiologi {
    id: number;
    nama_kategori: string;
    status: number;
    deleted_at: string | null;
    created_at: string;
    updated_at: string;
    parameter_radiologi: ParameterRadiologi[];
}

interface KelasRawat {
    id: number;
    kelas: string;
    urutan: string;
    keterangan: string;
    isICU: number;
    deleted_at: string | null;
    created_at: string;
    updated_at: string;
}

interface OrderLaboratorium {
    id: number;
    registration_id: number;
    otc_id: number | null;
    dokter_laboratorium_id: number;
    user_id: number;
    order_date: string;
    inspection_date: string | null;
    result_date: string | null;
    no_order: string;
    tipe_order: string;
    tipe_pasien: string;
    diagnosa_klinis: string;
    status_isi_hasil: string;
    status_billed: string;
    deleted_at: string | null;
    created_at: string;
    updated_at: string;
}

interface MakananGizi {
    id: number;
    created_at: string;
    updated_at: string;
    nama: string;
    harga: number;
    aktif: number;
}

interface OrderMakananGizi {
    id: number;
    created_at: string;
    updated_at: string;
    order_id: number;
    makanan_id: number;
    harga: number;
    persentase_habis: number;
    food: MakananGizi;
}

interface OrderGizi {
    id: number;
    created_at: string;
    updated_at: string;
    registration_id: number;
    kategori_id: number;
    untuk: string;
    tanggal_order: string;
    waktu_makan: string;
    ditagihkan: number;
    digabung: number;
    total_harga: number;
    status_payment: number;
    status_order: number;
    nama_pemesan: string;
    registration: Registration;
    foods: OrderMakananGizi[];
}

interface Satuan {
    id: number;
    deleted_at: string | null;
    created_at: string;
    updated_at: string;
    kode: string;
    nama: string;
    aktif: number;
}

interface KategoriBarang {
    id: number;
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
    nama: string;
    coa_inventory: number;
    coa_sales_outpatient: number | null;
    coa_cogs_outpatient: number | null;
    coa_sales_inpatient: number | null;
    coa_cogs_inpatient: number | null;
    coa_adjustment_daily: number | null;
    coa_adjustment_so: number | null;
    konsinsyasi: number;
    aktif: number;
    kode: string;
}

interface BarangFarmasi {
    id: number;
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
    nama: string;
    kode: string;
    keterangan: string | null;
    hna: number;
    ppn: number;
    ppn_rajal: number;
    ppn_ranap: number;
    tipe: "FN" | "NFN";
    formularium: "RS" | "NRS";
    jenis_obat: "paten" | "generik" | null;
    exp: "1w" | "2w" | "3w" | "1mo" | "2mo" | "3mo" | "6mo" | null;
    aktif: boolean;
    kategori_id: number;
    golongan_id: number | null;
    kelompok_id: number | null;
    satuan_id: number;
    principal: string | null;
    harga_principal: number | null;
    diskon_principal: number | null;
    restriksi: string | null;

    satuan?: Satuan;
    golongan?: GolonganBarang;
    kategori?: KategoriBarang;
    zat_aktif?: ZatAktifFarmasi[];
}

interface ZatAktif {
    id: number;
    deleted_at: string | null;
    created_at: string;
    updated_at: string;
    kode: string;
    nama: string;
    aktif: number;
}

interface ZatAktifFarmasi {
    id: number;
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
    zat_id: number;
    barang_id: number;
    zat?: ZatAktif;
}

interface BarangNonFarmasi {
    id: number;
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
    nama: string;
    kode: string;
    keterangan: string | null;
    hna: number;
    ppn: number;
    aktif: boolean;
    jual_pasien: boolean;
    kategori_id: number;
    golongan_id: number | null;
    kelompok_id: number | null;
    satuan_id: number;

    satuan?: Satuan;
    golongan?: GolonganBarang;
    kategori?: KategoriBarang;
}

interface MasterGudang {
    id: number;
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
    nama: string;
    cost_center: string;
    apotek: boolean;
    warehouse: boolean;
    aktif: boolean;
}

interface MinMaxStock {
    id: number;
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
    barang_f_id: number;
    barang_nf_id: number;
    gudang_id: number;
    min: number;
    max: number;
}

interface ItemPO {
    id: number;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;
    po_id: number;
    pri_id: number | null;
    barang_id: number;
    barang?: BarangFarmasi | BarangNonFarmasi;
    kode_barang: string;
    nama_barang: string;
    unit_barang: string;
    harga_barang: number;
    qty: number;
    qty_bonus: number;
    subtotal: number;
    discount_nominal: number;
    qty_received: number;
}

interface Supplier {
    id: number;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;
    kategori: string;
    nama: string;
    alamat: string;
    phone: string;
    fax: string;
    email: string;
    contact_person: string;
    contact_person_phone?: string | null;
    contact_person_email: string;
    no_rek: string;
    bank: string;
    top?: string | null;
    tipe_top: string;
    ppn: number;
    aktif: number;
}

interface PurchaseOrder {
    id: number;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;
    kode_po: string;
    user_id: number;
    app_user_id: number;
    ceo_app_user_id: number;
    supplier_id: number;
    tanggal_po: string;
    tanggal_app: string;
    tanggal_app_ceo: string;
    tanggal_kirim: string;
    is_auto: number;
    top: string;
    tipe_top: string;
    tipe: string;
    status: string;
    approval: string;
    approval_ceo: string;
    ppn: number;
    nominal: number;
    pic_terima: string;
    keterangan: string;
    keterangan_approval?: string | null;
    keterangan_approval_ceo?: string | null;
    items?: ItemPO[];
    supplier: Supplier;
}

interface GolonganBarang {
    id: number;
    deleted_at: Date | null;
    created_at: string; // Adjusted to string as per the original format
    updated_at: string; // Adjusted to string as per the original format
    kode: string;
    nama: string;
    aktif: number;
}

interface StoredItem {
    id: number;
    created_at: string; // Assuming it's in ISO 8601 format
    updated_at: string; // Assuming it's in ISO 8601 format
    deleted_at: string | null; // Assuming a nullable timestamp or null if deleted_at is unset
    pbi_id: number;
    gudang_id: number;
    qty: number;

    pbi?: PenerimaanBarangItem;
}


interface FarmasiResep {
    id: number;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;
    order_date: string;
    registration_id: number;
    otc_id: number;
    re_id: number;
    dokter_id: number;
    user_id: number;
    gudang_id: number;
    kode_resep: string;
    alamat: string;
    resep_manual: string;
    embalase: 'tidak' | 'item' | 'racikan';
    no_telp: string;
    total: number;
    bmhp: boolean;
    kronis: boolean;
    billed: boolean;
    handed: boolean;
    dispensing: boolean;

    items: FarmasiResepItems[];
}

interface FarmasiResepItems {
    id: number;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;
    resep_id: number;
    si_id: number;
    racikan_id: number;
    tipe: 'obat' | 'racikan';
    signa: string;
    instruksi: string;
    jam_pemberian: string;
    qty: number;
    harga: number;
    embalase: number;
    subtotal: number;

    stored?: StoredItem;
    resep?: FarmasiResep;
}

interface TelaahResep {
    id: number;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;
    resep_id: number;
    kejelasan_tulisan: boolean;
    benar_pasien: boolean;
    benar_nama_obat: boolean;
    benar_dosis: boolean;
    benar_waktu_dan_frekeunsi_pemberian: boolean;
    benar_rute_dan_cara_pemberian: boolean;
    ada_alergi_dengan_obat_yang_diresepkan: boolean;
    ada_duplikat_obat: boolean;
    interaksi_obat_yang_mungkin_terjadi: boolean;
    hal_lain_yang_mungkin_terjadi: boolean;
    hal_lain_yang_merupakan_masalah_dengan_obat: boolean;
    perubahan_resep_tertulis_1?: string | null;
    perubahan_resep_menjadi_1?: string | null;
    perubahan_resep_petugas_1?: string | null;
    perubahan_resep_disetujui_1?: string | null;
    perubahan_resep_tertulis_2?: string | null;
    perubahan_resep_menjadi_2?: string | null;
    perubahan_resep_petugas_2?: string | null;
    perubahan_resep_disetujui_2?: string | null;
    perubahan_resep_tertulis_3?: string | null;
    perubahan_resep_menjadi_3?: string | null;
    perubahan_resep_petugas_3?: string | null;
    perubahan_resep_disetujui_3?: string | null;
    alamat_no_telp_pasien?: string | null;

    resep?: FarmasiResep;
}

interface PenerimaanBarangItem {
    id: number;
    created_at: string; // Assuming it's in ISO 8601 format
    updated_at: string; // Assuming it's in ISO 8601 format
    deleted_at: string | null; // Nullable timestamp or null if deleted_at is unset
    pb_id: number;
    poi_id: number;
    barang_id: number;
    satuan_id: number;
    nama_barang: string;
    kode_barang: string;
    unit_barang: string;
    batch_no: string;
    tanggal_exp: string | null; // Nullable if it's a date or can be unset
    qty: number;
    harga: number;
    diskon_nominal: number;
    subtotal: number;
    is_bonus: number;

    pb?: PenerimaanBarang;
    item?: BarangFarmasi | BarangNonFarmasi;
    satuan?: Satuan;
}

interface PenerimaanBarang {
    id: number;
    created_at: string; // Assuming it's in ISO 8601 format
    updated_at: string; // Assuming it's in ISO 8601 format
    deleted_at: string | null; // Nullable timestamp or null if deleted_at is unset
    tanggal_terima: string; // Expected to be a date in string format
    tanggal_faktur: string | null; //Nullable if it has associated data, otherwise can be null/undefined
    kode_penerimaan: string;
    no_faktur: string;
    pic_penerima: string; // Typically the recipient's name or ID as string
    keterangan: string;
    ppn: number;
    ppn_nominal: number;
    materai: number;
    diskon_faktur: number;
    total: number;
    total_final: number;
    user_id: number;
    gudang_id: number;
    supplier_id: number;
    po_id: number;
    tipe_bayar: string;
    tipe_terima: "po" | "non_po"; // Depending on your actual data, you can make it more specific
    status: string;
    kas: string | null; // Nullable if not all entries contain this field or set to undefined
}

// Define StockRequestItem interface for nested items array
interface StockRequestItem {
    id: number;
    created_at: string; // Timestamp in ISO format
    updated_at: string; // Timestamp in ISO format
    deleted_at: string | null; // Nullable timestamp or null
    sr_id: number;
    barang_id: number;
    satuan_id: number;
    qty: number;
    qty_fulfilled: number;
    keterangan: string;
    barang?: BarangFarmasi | BarangNonFarmasi
    satuan?: Satuan
}

// Define the main StockRequest interface
interface StockRequest {
    id: number;
    created_at: string; // Timestamp in ISO format
    updated_at: string; // Timestamp in ISO format
    deleted_at: string | null; // Nullable timestamp or null
    tanggal_sr: string; // Date in YYYY-MM-DD format
    user_id: number;
    asal_gudang_id: number;
    tujuan_gudang_id: number;
    kode_sr: string;
    keterangan: string;
    tipe: "normal" | "urgent"; // String literal if only specific values are allowed
    status: "final" | "draft"; // Specify all possible statuses; replace 'other_status' as needed
    items?: StockRequestItem[]; // Array of StockRequestItem
}

interface StockOpnameItem {
    kode_so: string;
    id: number;
    created_at: Date; // Assuming standard timestamp fields are managed by the ORM/database
    updated_at: Date;
    deleted_at?: Date; // Optional for soft deletion support
    user_id: number;
    sog_id: number;
    si_f_id?: number;
    si_nf_id?: number;
    qty: number;
    keterangan?: string;
    status: 'draft' | 'final';
}

interface StackedStoredItemOpname {
    actual?: number;
    frozen: number;
    movement: number;
    qty: number;
    barang_id: number;
    satuan_id: number;
    barang: BarangFarmasi | BarangNonFarmasi;
    satuan: Satuan
    type: "f" | "nf";
    stack: StoredItemOpname[];
}

interface Employee {
    id: number;
    company_id?: number;
    organization_id?: number;
    job_position_id?: number;
    job_level_id?: number;
    approval_line?: number;
    approval_line_parent?: number;
    employee_code?: string;
    title?: string;
    fullname?: string;
    degree?: string;
    email?: string;
    mobile_phone?: string;
    place_of_birth?: string;
    birthdate?: string;
    gender?: string;
    marital_status?: string;
    blood_type?: string;
    religion?: string;
    last_education?: string;
    identity_type?: string;
    identity_number?: string;
    identity_expire_date?: string;
    postal_code?: string;
    citizen_id_address?: string;
    residental_address?: string;
    barcode?: string;
    employment_status?: string;
    join_date?: string;
    end_status_date?: string;
    resign_date?: string;
    basic_salary?: string;
    salary_type?: string;
    payment_schedule?: string;
    protate_setting?: string;
    allowed_for_overtime?: boolean;
    npwp?: string;
    ptkp_status?: string;
    tax_methode?: string;
    tax_salary?: string;
    taxable_date?: string;
    employment_tax_status?: string;
    beginning_netto?: string;
    pph21_paid?: string;
    bpjs_ker_number?: string;
    npp_ker_bpjs?: string;
}

interface User {
    id: number;
    name: string;
    email: string;
    is_active: boolean;
    avatar?: string;
    remember_token?: string;
    created_at: string; // Assuming this is a timestamp in ISO format
    updated_at: string; // Assuming this is a timestamp in ISO format
    employee?: Employee;
}

interface DistribusiBarang {
    id: number;
    created_at: string; // Assuming this is a timestamp in ISO format
    updated_at: string; // Assuming this is a timestamp in ISO format
    deleted_at: string | null; // Assuming this is a timestamp in ISO format for soft deletes
    tanggal_db: string; // Date type for distribution date
    user_id: number;
    asal_gudang_id: number;
    tujuan_gudang_id: number;
    sr_id: number | null;
    kode_db: string;
    keterangan: string | null;
    status: "draft" | "final";
}

interface ReturBarang {
    id: number;
    created_at: string; // Assuming this is a timestamp in ISO format
    updated_at: string; // Assuming this is a timestamp in ISO format
    deleted_at: string | null; // Assuming this is a timestamp in ISO format for soft deletes
    tanggal_retur: string; // Date type for return date
    user_id: number;
    supplier_id: number;
    keterangan: string | null;
    kode_retur: string;
    ppn: number;
    ppn_nominal: number;
    nominal: number;
}



interface StockTransactions {
    id: number;
    created_at: string; // Timestamp in ISO format
    updated_at: string; // Timestamp in ISO format
    stock_id: number;
    stock_model: string;
    source_id: number;
    source_model: string;
    source_controller: string;
    event_type: "create" | "update";
    transaction_type: "in" | "out";
    before_qty: number | null;
    after_qty: number;
    before_gudang_id: number | null;
    after_gudang_id: number;
    performed_by: number;
    keterangan?: string;
    
    stock?: StoredItem;
    source?: StockTransactionsSources;
    user?: User;
    before_gudang?: MasterGudang;
    after_gudang?: MasterGudang;
}

type StockDetails = (BarangFarmasi | BarangNonFarmasi) & {
    qty_start: number,
    qty_finish: number,
    qty_in: number,
    qty_out: number,
    adjustment: number,
    qty_expired: number,
    logs: StockTransactions[],
    stored_items: StoredItem[]
}


type StoredItemOpname = StoredItem & {
    frozen: number;
    movement: number;
    type: "f" | "nf";
    opname?: StockOpnameItem;
}

interface ResepElektronik {
    id: number;
    created_at: string; // Assuming this is a timestamp in ISO format
    updated_at: string; // Assuming this is a timestamp in ISO format
    deleted_at: string | null; // Assuming this is a timestamp in ISO format for soft deletes
    cppt_id: number;
    user_id: number;
    registration_id: number;
    gudang_id: number | null;
    kode_re: string;
    resep_manual: string;
    total: number;
    processed: number;

    registration?: Registration;
    cppt?: CPPT;
    items?: ResepElektronikItem[];
}

interface CPPT {
    id: number;
    created_at: string;
    updated_at: string;
    deleted_at: string | null;
    user_id: number;
    registration_id: number;
    tipe_cppt: string;
    tipe_rawat: string;
    doctor_id: number | null;
    konsulkan_ke: number | null;
    subjective: string;
    objective: string;
    assesment: string;
    planning: string;
    instruksi: string | null;
    evaluasi: string | null;
    implementasi: string | null;
}

interface ResepElektronikItem {
    id: number;
    created_at: string;
    updated_at: string;
    re_id: number | null;
    barang_id: number;
    satuan_id: number;
    qty: number;
    harga: number;
    subtotal: number;
    signa: string;
    instruksi: string;
    billed: number;

    barang?: BarangFarmasi;
}


type StockTransactionsSources = PenerimaanBarang | DistribusiBarang | ReturBarang | StockOpnameItem;
type PatientType = "rajal" | "ranap" | "otc";
type SumberItem = "npr" | "pr";
type TipePR = "all" | "normal" | "urgent";
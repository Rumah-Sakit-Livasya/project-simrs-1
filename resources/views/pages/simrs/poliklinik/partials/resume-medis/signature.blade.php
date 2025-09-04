<table class="table table-borderless">
    <tbody>
        <tr>
            <td style="width: 65%"></td>
            <td style="width: 35%">
                <div class="text-center">
                    DPJP/Dokter Yang Memeriksa
                </div>
                <div class="text-center">
                    <a class="btn btn-primary btn-ttd-resume-medis btn-sm text-white my-2"
                        data-id="{{ auth()->user()->id }}">
                        Tanda Tangan
                    </a>
                    <img id="signature-display" src="" alt="Signature Image" style="display:none; max-width:80%;">
                    <br>
                    <span>{{ auth()->user()->employee->fullname }}</span>
                </div>
            </td>
            <td style="width: 10%">
                <input type="hidden" name="is_ttd">
            </td>
        </tr>
    </tbody>
</table>

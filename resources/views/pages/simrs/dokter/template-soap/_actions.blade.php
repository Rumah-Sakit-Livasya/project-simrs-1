<a href="{{ route('dokter.template-soap.edit', $row->id) }}" class="btn btn-warning btn-sm btn-icon" title="Edit">
    <i class="fal fa-edit"></i>
</a>
<form action="{{ route('dokter.template-soap.destroy', $row->id) }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm btn-icon btn-delete" title="Hapus">
        <i class="fal fa-trash-alt"></i>
    </button>
</form>

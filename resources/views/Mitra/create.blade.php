@extends('main')

@section('content')

<h1>New Data</h1>
<form action="/Mitra/create" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="nama_lengkap" class="form-label">Nama</label>
        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Nama">
    </div>
    <div class="mb-3">
        <label for="nis" class="form-label">NIS</label>
        <input type="text" class="form-control" id="nis" name="nis" placeholder="NIS">
    </div>
    <div class="mb-3">
        <label for="no_dompet_digital" class="form-label">No Dompet Digital</label>
        <input type="text" class="form-control" id="no_dompet_digital" name="no_dompet_digital" placeholder="Nomor Dompet Digital">
    </div>
    <div class="mb-3">
        <label for="image_id_card" class="form-label">Image</label>
        <input type="file" class="form-control" id="image_id_card" name="image_id_card">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
    
@endsection

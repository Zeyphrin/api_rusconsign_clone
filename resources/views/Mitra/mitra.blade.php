@extends('main')

@section('content')

<h1>Halaman Request Mitra</h1>

<button type="button" class="btn btn-dark mb-3" onclick="window.location.href='/Mitra/create'">Tambah Data</button>

<div class="table-responsive mx-auto" style="max-width: 2000px;">
    <table class="table table-bordered table-sm" id="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nama Lengkap</th>
                <th scope="col">NIS</th>
                <th scope="col">No Dompet Digital</th>
                <th scope="col">Image</th>
                <th scope="col">Status</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mitras as $mitra)
            <tr>
                <td>{{ $mitra->id }}</td>
                <td>{{ $mitra->nama_lengkap }}</td>
                <td>{{ $mitra->nis }}</td>
                <td>{{ $mitra->no_dompet_digital }}</td>
                <td>
                    @if ($mitra->image_id_card)
                    <img src="{{ asset('storage/' . $mitra->image_id_card) }}" alt="ID Card" style="max-width: 100px;">
                    @else
                    No Image
                    @endif
                </td>
                <td>{{ $mitra->status }}</td>
                <td>
                    <a href="/mitra/{{ $mitra->id }}/edit" class="btn btn-primary btn-sm">Edit</a>
                    <form action="/mitra/{{ $mitra->id }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection

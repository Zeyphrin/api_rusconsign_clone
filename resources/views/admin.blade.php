@extends('main')

@section('content')
    <h1>Ini adalah halaman Admin Mitra!</h1>

    <div class="container mt-5">
       
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Nis</th>
                    <th scope="col">Dompet Digital</th>
                    <th scope="col">Foto ID Card</th>
                    <th scope="col">Action</th>
                  
                </tr>
            </thead>
            <tbody>          
               <tr>
               <td>1</td>
               <td>hahha</td>
               <td>ffff</td>
               <td>ffff</td>
               <td>""</td>
               <td>
                <button class="btn btn-danger">Tolak</button> 
                <button class="btn btn-success">Terima</button>
               </td>              
            </tr> 
            </tbody>
        </table>
    </div>
@endsection
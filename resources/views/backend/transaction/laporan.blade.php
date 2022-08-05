<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
        integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <title> Daftar Laporan</title>
</head>

<body>
    {{-- // Tambahin Image dimari --}}
    <img src="" alt="">  
    <div class="container">
        <div class="row">
            <table class="table table-borderless col-md-8">
                <thead class="font-weight-bold">
                    <tr>
                        <td>
                            Perihal 
                        </td>
                        <td>
                            : Hasil Laporan Penjualan Restaurant Dapur Bunda Bahagia
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Tanggal Laporan 
                        </td>
                        <td>
                            : {{ date('d M Y', strtotime($df)) }} Sampai Dengan {{ date('d M Y', strtotime($dt)) }}
                        </td> 
                    </tr>
                </thead>
            </table>
        </div>
        <h3 class="text-center mt-5"> <strong>Laporan Penjualan </strong> </h3>
        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th class="border-top-0">Nama</th>
                    <th class="border-top-0">Total Barang</th>
                    <th class="border-top-0">Tanggal</th>
                    <th class="border-top-0"> Harga </th>
                </tr>
            </thead>
            <tbody>
                @php $totalharga = 0; @endphp
                @foreach ($data as $item)
                    <tr>
                        @php
                          $getName = App\Models\User::where('id',$item->users_id)->first();
                        @endphp
                        <td> {{$getName-> name}}</td>
                        <td> {{$item->transactiondetail->quantity}}</td>
                        <td> {{ date('d M Y', strtotime($df)) }}</td>
                        <td> {{$item->total_price}}</td>
                    </tr>
                    @php
                        $totalharga += $item->total_price;
                    @endphp
                @endforeach
            </tbody>
        </table>

        <p class="mt-5"> Berikut adalah daftar laporan hasil penjualan pada tanggal {{ date('d M Y', strtotime($df)) }} Sampai Dengan {{ date('d M Y', strtotime($dt)) }} dengan penghasilan  {{$totalharga}} </p>
    </div>
    <script>
        // window.addEventListener("load", window.print());
    </script>
</body>
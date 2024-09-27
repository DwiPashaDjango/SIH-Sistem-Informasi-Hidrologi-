@extends('layouts.admin')

@section('title')
    Buat Uji Kualitas Air
@endsection

@push('css')
    <link rel="stylesheet" href="{{asset('admin')}}/modules/select2/dist/css/select2.min.css">
@endpush    

@section('content')
@if (session()->has('message'))
    <div class="alert alert-primary">
        {{session()->get('message')}}
    </div>
@endif
<form method="POST" action="{{route('water.quality.generateTestQualityWater')}}">
    @csrf
    <input type="hidden" name="pos_id" id="pos_id" value="{{$pos->id}}">

    <div class="card">
        <div class="card-body">
            <div class="form-group mb-3">
                <label for="">Semester</label>
                <select name="semester" id="semester" class="form-control select2" required>
                    <option value="">- Pilih -</option>
                    <option value="1">Semester 1</option>
                    <option value="2">Semester 2</option>
                </select>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="bg-primary">
                    <tr>
                        <th class="text-white text-center">No</th>
                        <th class="text-white text-center">Parameter</th>
                        <th class="text-white text-center">Satuan</th>
                        <th class="text-white text-center">Hasil Uji</th>
                        <th class="text-white text-center">#</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Bagian Fisika -->
                    <tr>
                        <td></td>
                        <td colspan="3" class="text-start"><strong>Fisika</strong></td>
                    </tr>
                    <tr class="">
                        <td class="text-center">1</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="pH"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="-"></td>
                        <td class="text-center"><input type="text" name="ph" class="form-control text-center"></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">2</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Suhu Air"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="C"></td>
                        <td class="text-center"><input type="text" name="suhu" class="form-control text-center" ></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">3</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Zat Terlarut"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="zat" class="form-control text-center" ></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">4</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Orp"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Mvorp"></td>
                        <td class="text-center"><input type="text" name="orp" class="form-control text-center" ></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">5</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Conductivity"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="us/cm"></td>
                        <td class="text-center"><input type="text" name="conductivity" class="form-control text-center" ></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">6</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Resistivity"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="cm"></td>
                        <td class="text-center"><input type="text" name="resistivity" class="form-control text-center" ></td>
                        <td class="text-center">-</td>
                    </tr>
    
                    <!-- Bagian Kimia -->
                    <tr>
                        <td></td>
                        <td colspan="3" class="text-start"><strong>Kimia</strong></td>
                    </tr>
                    <tr class="">
                        <td class="text-center">1</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Oksigen Terlarut/DO"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="oksigen" class="form-control text-center" ></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">2</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Cod"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="cod" class="form-control text-center" ></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">3</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Khlorida"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="khlorida" class="form-control text-center" ></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">4</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Nitrit"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="nitrit" class="form-control text-center" ></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">5</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Nitrat"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="nitrat" class="form-control text-center" ></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">6</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Sulfat"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="sulfat" class="form-control text-center" ></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">7</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Phospat"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="phospat" class="form-control text-center" ></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">8</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Amonia"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="amonia" class="form-control text-center" ></td>
                        <td class="text-center">-</td>
                    </tr>
    
                    <!-- Bagian Logam -->
                    <tr>
                        <td></td>
                        <td colspan="3" class="text-start"><strong>Logam</strong></td>
                    </tr>
                    <tr class="">
                        <td class="text-center">1</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Tembaga"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="tembaga" class="form-control text-center" ></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">2</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Mangan"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="mangan" class="form-control text-center" ></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">3</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Chrom"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="chrom" class="form-control text-center" ></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">4</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Seng"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="seng" class="form-control text-center" ></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">5</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Besi"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="besi" class="form-control text-center" ></td>
                        <td class="text-center">-</td>
                    </tr>
                </tbody>
                <tr>
                    <td></td>
                    <td colspan="3" class="text-start"><strong>Lainnya</strong></td>
                </tr>
                <thead class="bg-primary">
                    <tr>
                        <th class="text-white text-center">No</th>
                        <th class="text-white text-center">Parameter</th>
                        <th class="text-white text-center">Satuan</th>
                        <th class="text-white text-center">Hasil Uji</th>
                        <th class="text-white text-center">#</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    <tr class="data-row">
                        <td class="text-center">1</td>
                        <td class="text-center">
                            <input type="text" name="parameter[]" id="parameter" class="form-control" >
                        </td>
                        <td class="text-center">
                            <input type="text" name="satuan[]" id="satuan" class="form-control" >
                        </td>
                        <td class="text-center">
                            <input type="text" name="hasil[]" id="hasil" class="form-control" >
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-success add-row"><i class="fas fa-plus"></i></button>
                        </td>
                    </tr>
                </tbody>
                <tfoot class="bg-primary">
                    <tr>
                        <th colspan="4" class="text-center text-white">Total</th>
                        <th><input type="text" name="total" id="total" class="form-control text-center" ></th>
                    </tr>
                </tfoot>
            </table>
    
            <div class="mt-3">
                <button type="submit" class="btn btn-primary w-100">Generate</button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('js')
    <script src="{{asset('admin')}}/modules/select2/dist/js/select2.full.min.js"></script>
    <script>
        let rowNumber = 1;

        function updateRowNumbers() {
            document.querySelectorAll('#tbody tr').forEach((row, index) => {
                row.querySelector('td:first-child').textContent = index + 1;
            });
        }

        document.addEventListener('click', function(event) {
            if (event.target.closest('.add-row')) {
                rowNumber++;
                const newRow = `
                    <tr class="data-row">
                        <td class="text-center">${rowNumber}</td>
                        <td class="text-center">
                            <input type="text" name="parameter[]" class="form-control" >
                        </td>
                        <td class="text-center">
                            <input type="text" name="satuan[]" class="form-control" >
                        </td>
                        <td class="text-center">
                            <input type="text" name="hasil[]" class="form-control" >
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger remove-row"><i class="fas fa-minus"></i></button>
                        </td>
                    </tr>
                `;
                document.querySelector('#tbody').insertAdjacentHTML('beforeend', newRow);
                updateRowNumbers();
            }
        });

        document.addEventListener('click', function(event) {
            if (event.target.closest('.remove-row')) {
                const row = event.target.closest('tr');
                if (document.querySelectorAll('#tbody tr').length > 1) {
                    row.remove();
                    updateRowNumbers();
                }
            }
        });

    </script>
@endpush
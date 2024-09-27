@extends('layouts.admin')

@section('title')
    Edit Uji Kualitas Air
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
<form method="POST" action="{{route('water.quality.update', ['id' => $qualityWater->id])}}">
    @csrf
    @method("PUT")
    <div class="card">
        <div class="card-body">
            <div class="form-group mb-3">
                <label for="">Semester</label>
                <select name="semester" id="semester" class="form-control select2" required>
                    <option value="">- Pilih -</option>
                    <option value="1" {{$qualityWater->semester == 1 ? 'selected' : ''}}>Semester 1</option>
                    <option value="2" {{$qualityWater->semester == 2 ? 'selected' : ''}}>Semester 2</option>
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
                        <td class="text-center"><input type="text" name="ph" value="{{$qualityWater->ph}}" class="form-control text-center" required></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">2</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Suhu Air"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="C"></td>
                        <td class="text-center"><input type="text" name="suhu" value="{{$qualityWater->suhu}}" class="form-control text-center" required></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">3</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Zat Terlarut"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="zat" value="{{$qualityWater->zat}}" class="form-control text-center" required></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">4</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Orp"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Mvorp"></td>
                        <td class="text-center"><input type="text" name="orp" value="{{$qualityWater->orp}}" class="form-control text-center" required></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">5</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Conductivity"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="us/cm"></td>
                        <td class="text-center"><input type="text" name="conductivity" value="{{$qualityWater->conductivity}}" class="form-control text-center" required></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">6</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Resistivity"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="cm"></td>
                        <td class="text-center"><input type="text" name="resistivity" value="{{$qualityWater->resistivity}}" class="form-control text-center" required></td>
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
                        <td class="text-center"><input type="text" name="oksigen" value="{{$qualityWater->oksigen}}" class="form-control text-center" required></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">2</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Cod"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="cod" value="{{$qualityWater->cod}}" class="form-control text-center" required></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">3</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Khlorida"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="khlorida" value="{{$qualityWater->khlorida}}" class="form-control text-center" required></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">4</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Nitrit"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="nitrit" value="{{$qualityWater->nitrit}}" class="form-control text-center" required></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">5</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Nitrat"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="nitrat" value="{{$qualityWater->nitrat}}" class="form-control text-center" required></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">6</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Sulfat"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="sulfat" value="{{$qualityWater->sulfat}}" class="form-control text-center" required></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">7</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Phospat"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="phospat" value="{{$qualityWater->phospat}}" class="form-control text-center" required></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">8</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Amonia"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="amonia" value="{{$qualityWater->amonia}}" class="form-control text-center" required></td>
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
                        <td class="text-center"><input type="text" name="tembaga" value="{{$qualityWater->tembaga}}" class="form-control text-center" required></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">2</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Mangan"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="mangan" value="{{$qualityWater->mangan}}" class="form-control text-center" required></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">3</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Chrom"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="chrom" value="{{$qualityWater->chrom}}" class="form-control text-center" required></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">4</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Logam"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="logam" value="{{$qualityWater->logam}}" class="form-control text-center" required></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">5</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Seng"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="seng" value="{{$qualityWater->seng}}" class="form-control text-center" required></td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr class="">
                        <td class="text-center">6</td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="Besi"></td>
                        <td class="text-center"><input type="text" readonly class="form-control text-center" value="mg/l"></td>
                        <td class="text-center"><input type="text" name="besi" value="{{$qualityWater->besi}}" class="form-control text-center" required></td>
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
                    @if (count($qualityWater->detail) > 0)
                        <input type="hidden" name="action" id="action" value="2">
                        @foreach ($qualityWater->detail as $index => $item)
                            <tr class="data-row">
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td class="text-center">
                                    <input type="text" name="parameter[]" value="{{$item->parameter}}" id="parameter" class="form-control" required>
                                </td>
                                <td class="text-center">
                                    <input type="text" name="satuan[]" value="{{$item->satuan}}" id="satuan" class="form-control" required>
                                </td>
                                <td class="text-center">
                                    <input type="text" name="hasil[]" value="{{$item->hasil}}" id="hasil" class="form-control" required>
                                </td>
                                <td class="text-center">
                                    @if ($index == 0)
                                        <button type="button" class="btn btn-success add-row"><i class="fas fa-plus"></i></button>
                                    @else
                                        <button type="button" class="btn btn-danger remove-row"><i class="fas fa-minus"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <input type="hidden" name="action" id="action" value="1">
                    @endif
                </tbody>
                <tfoot class="bg-primary">
                    <tr>
                        <th colspan="4" class="text-center text-white">Total</th>
                        <th><input type="text" name="total" id="total" value="{{$qualityWater->total}}" class="form-control text-center" required></th>
                    </tr>
                </tfoot>
            </table>
    
            <div class="mt-3">
                <button type="submit" class="btn btn-primary w-100">Ubah</button>
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
                            <input type="text" name="parameter[]" class="form-control" required>
                        </td>
                        <td class="text-center">
                            <input type="text" name="satuan[]" class="form-control" required>
                        </td>
                        <td class="text-center">
                            <input type="text" name="hasil[]" class="form-control" required>
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
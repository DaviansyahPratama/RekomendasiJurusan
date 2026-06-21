@extends('layouts.app')

@section('content')
<h2 class="mb-4">Pilih Bidang Minat</h2>
<div class="card border-0 shadow-sm"><div class="card-body">
    <form action="{{ route('mahasiswa.pilih-minat.store') }}" method="POST">
        @csrf
        <p class="text-muted">Pilih satu atau lebih bidang minat yang sesuai dengan Anda.</p>
        <div class="row">
            @foreach($minatList as $minat)
            <div class="col-md-4 mb-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="minat_ids[]" value="{{ $minat->id }}"
                        id="minat_{{ $minat->id }}"
                        @checked($user->minat->contains($minat->id))>
                    <label class="form-check-label" for="minat_{{ $minat->id }}">{{ $minat->nama_minat }}</label>
                </div>
            </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary mt-3">Simpan Minat</button>
    </form>
</div></div>
@endsection

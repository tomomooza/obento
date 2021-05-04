@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    obentoシステムにログインしました!
                    <p><a href="menu"><input type="button" value="お弁当メニュー表"></a></p>
                    <p><a href="obento"><input type="button" value="お弁当登録"></a></p>
                    <p><a href="dish"><input type="button" value="お料理登録"></a></p>
                    <p><a href="season"><input type="button" value="旬の食材及び食材メモ登録"></a></p>
                    <p><a href="ingredient"><input type="button" value="食材登録変更"></a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

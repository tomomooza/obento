@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1>食材登録変更</h1>
            @isset($error)
              <div class="alert alert-danger">{{ $error }}</div>
            @endisset
            <div class="card">
                <div class="card-header">新規登録</div>

                <div class="card-body">
                    <form method="post">
                    @csrf
                    <p>食材名:<input type="text" name="ingredient"></p>
                    <p>食品目分類:<select name="category">
                      <option value="糖質">糖質</option>
                      <option value="肉類">肉類</option>
                      <option value="魚類">魚類</option>
                      <option value="豆類">豆類</option>
                      <option value="野菜">野菜</option>
                      <option value="果物">果物</option>
                    </select></p>
                    <p><input type="submit" value="登録ボタン"></p>
                    </form>
                </div>
            </div>
            <p></p>
            <div class="card">
                <div class="card-header">食材変更</div>

                <div class="card-body">
                    <form method="post">
                    @method('put')
                    @csrf
                    <select name="select_ingredient" id="select_ingredient">
                      <option value=""></option>
                      @foreach($ingredients_data as $v)
                      <option value="{{ $v['id']}}">{{ $v['ingredient']}}</option>
                      @endforeach
                    </select>
                    <p>食材名:<input type="text" name="ingredient"></p>
                    <p>食品目分類:<select name="category">
                      <option value="糖質">糖質</option>
                      <option value="肉類">肉類</option>
                      <option value="魚類">魚類</option>
                      <option value="豆類">豆類</option>
                      <option value="野菜">野菜</option>
                      <option value="果物">果物</option>
                    </select></p>
                    <p><input type="submit" value="登録ボタン"></p>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

<script src="/js/app.js"></script>
<script>
const ingredients = @json($ingredients_data);
$(function(){
  $('#select_ingredient').change(function(){
    for (let i = 0; i < ingredients.length; i++) {
      if (ingredients[i]['id'] == $('#select_ingredient').val()) {
        $('#change_ingredient').val(ingredients[i]['ingredient']);
        $('#change_category').val(ingredients[i]['category']);
        break;
      }
    }
  });
});
</script>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1>食材登録・変更</h1>
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
                      <option value="卵類">卵類</option>
                      <option value="魚介類">魚介類</option>
                      <option value="豆類">豆類</option>
                      <option value="野菜">野菜</option>
                      <option value="乳製品">乳製品</option>
                      <option value="加工品">加工品</option>
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
                    <p>食材名:<input type="text" name="ingredient" id="change_ingredient"></p>
                    <p>食品目分類:<select name="category" id="change_category">
                      <option value="糖質">糖質</option>
                      <option value="肉類">肉類</option>
                      <option value="卵類">卵類</option>
                      <option value="魚介類">魚介類</option>
                      <option value="豆類">豆類</option>
                      <option value="野菜">野菜</option>
                      <option value="乳製品">乳製品</option>
                      <option value="加工品">加工品</option>
                      <option value="果物">果物</option>
                    </select></p>
                    <p><input type="submit" value="変更ボタン"></p>
                    </form>
                </div>
            </div>

            <p></p>
            <div class="text-right"><a href="/main"><input type="button" value="メイン画面に戻る" class="btn btn-info"></a></div>
        </div>
    </div>
</div>
@endsection

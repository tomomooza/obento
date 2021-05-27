@extends('layouts.app')

<script src="./js/app.js"></script>
<script>
const ingredients = @json($ingredients_data);
let ingredient_list = [];
$(function(){
  $('#category').change(function(){
    ingredient_list = [];
    $('#select_ingredient').html('<option value="">選択してください</option>');
    for (let i = 0; i < ingredients.length; i++) {
      if (ingredients[i]['category'] == $('#category').val()) {
        ingredient_list.push(ingredients[i]);
        $('#select_ingredient').append('<option value="'+ingredients[i]['id']+ '">' + ingredients[i]['ingredient'] + '</option>');
      }
    }
  });

  $('#select_ingredient').change(function(){
    $('#ingredients_id').val('');
    for (let i = 0; i < ingredient_list.length; i++) {
      if (ingredient_list[i]['id'] == $('#select_ingredient').val()) {
        for (let j = 1; j <= 12; j++) {
          if (ingredient_list[i]['season' + j] == '1') {
            $('#season' + j).prop('checked', true);
          } else {
            $('#season' + j).prop('checked', false);
          }
        }
        $('#memo').val(ingredient_list[i]['memo']);
        $('#seasons_id').val(ingredient_list[i]['seasons_id']);
        $('#ingredients_id').val(ingredient_list[i]['id']);
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
            <h1>旬の食材および食材メモ登録</h1>
            @isset($error)
              <div class="alert alert-danger">{{ $error }}</div>
            @endisset
            <div class="card">
                <div class="card-header">旬の食材および食材メモ登録</div>

                <div class="card-body">
                <p>食品目分類:<select id="category">
                      <option value="">選択してください</option>
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
                    <p>
                    食材名:
                    <select id="select_ingredient">
                    </select>
                    </p>
                    <form method="post">
                    @csrf
                    <p>旬</p>
                    <p>
                      <input type="checkbox" name="season1" id="season1" value="1">１月
                      <input type="checkbox" name="season2" id="season2" value="1">２月
                      <input type="checkbox" name="season3" id="season3" value="1">３月
                      <input type="checkbox" name="season4" id="season4" value="1">４月
                      <input type="checkbox" name="season5" id="season5" value="1">５月
                      <input type="checkbox" name="season6" id="season6" value="1">６月
                    </p>
                    <p>
                      <input type="checkbox" name="season7" id="season7" value="1">７月
                      <input type="checkbox" name="season8" id="season8"  value="1">８月
                      <input type="checkbox" name="season9" id="season9"  value="1">９月
                      <input type="checkbox" name="season10" id="season10"  value="1">１０月
                      <input type="checkbox" name="season11" id="season11"  value="1">１１月
                      <input type="checkbox" name="season12" id="season12"  value="1">１２月
                    </p>
                      <p></p>
                      <p>食材メモ</p>
                      <p><textarea name="memo" id="memo" rows="3" cols="40"></textarea></p>
                      <input type="hidden" name="seasons_id" id="seasons_id" value="">
                      <input type="hidden" name="ingredients_id" id="ingredients_id" value="">
                    <p><input type="submit" value="登録ボタン"></p>
                    </form>
                </div>
            </div>
            <p></p>
            <div class="text-right"><a href="./main"><input type="button" value="メイン画面に戻る" class=""></a></div>
        </div>
    </div>
</div>
@endsection

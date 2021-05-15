@extends('layouts.app')
<style>
  .p_dishes_ingredients {
    margin-bottom: 0;
  }
</style>
<script src="/js/app.js"></script>
<script>
$(function(){
  $('.abled').prop('disabled', true);
  $('#add_ingredient').hide();
  $('#bt_change_submit').hide();

  $('#bt_change').click(function(){
    $('.abled').prop('disabled', false);
    $('#add_ingredient').show();
    $('#bt_change').prop('disabled', true);
  });

  const ingredients = @json($ingredients_data);
  let ingredient_list = [];
  let dishes_ingredients_list = [];
  
  function make_ingredient_list() {
    ingredient_list = [];
    $('#select_ingredient').html('<option value="">選択してください</option>');
    for (let i = 0; i < ingredients.length; i++) {
      if (ingredients[i]['category'] == $('#select_category').val()) {
        if ($('#select_season').prop('selectedIndex') == 0) {
          ingredient_list.push(ingredients[i]);
          $('#select_ingredient').append('<option value="' + ingredients[i]['ingredients_id'] + '">' + ingredients[i]['ingredient'] + '</option>');
        } else {
          if (ingredients[i][$('#select_season').val()] == '1') {
            ingredient_list.push(ingredients[i]);
            $('#select_ingredient').append('<option value="' + ingredients[i]['ingredients_id'] + '">' + ingredients[i]['ingredient'] + '</option>');
          }
        }
      }
    }
  }

  function clear_select_ingredient() {
    for (let j = 1; j <= 12; j++) {
      $('#display_season' + j).prop('checked', false);
    }
    $('#display_memo').val('');
    $('#display_ingredients_id').val('');
  }

  function display_select_ingredient() {
    $('#display_ingredients_id').val('');
    for (let i =0; i < ingredient_list.length; i++) {
      if (ingredient_list[i]['ingredients_id'] == $('#select_ingredient').val()) {
        for (let j = 1; j <= 12; j++) {
          if (ingredient_list[i]['season' + j] == '1') {
            $('#display_season' + j).prop('checked', true);
          } else {
            $('#display_season' + j).prop('checked', false);
          }
        }
        $('#display_memo').val(ingredient_list[i]['memo']);
        $('#display_ingredients_id').val(ingredient_list[i]['ingredients_id']);
        break;
      }
    }
  }

  $('#select_category').change(function() {
    make_ingredient_list();
    clear_select_ingredient();
  });

  $('#select_season').change(function() {
    make_ingredient_list();
    clear_select_ingredient();
  });

  $('#select_ingredient').change(function() {
    display_select_ingredient();
  });

  function make_dishes_list() {
    $('#selected_ingredients').text('');
    for (let i = 0; i < dishes_ingredients_list.length; i++) {
      let ingredient = get_ingredients(dishes_ingredients_list[i]);
      let li = $('<li>');
      let e_ingredient = $('<div>');
      e_ingredient.html(ingredient['ingredient']);
      let inputd = $('<input type="button" class="float-right abled">');//input delete
      inputd.val('この食材を削除する');
      let inputi = $('<input type="hidden" name="ingredients[]">');
      inputi.val(ingredient['ingredients_id']);
      e_ingredient.append(inputd);
      e_ingredient.append(inputi);
      let syun = '旬:';
      for (let j = 1; j <= 12; j++) {
        if (ingredient['season' + j] == '1') {
          syun += j + '月 ';    
        }
      }
      e_ingredient.append('<p class="p_dishes_ingredients">' + syun + '</p>');
      e_ingredient.append('<p class="p_dishes_ingredients">食材メモ:' + ingredient['memo'] + '</p>');
      e_ingredient.append('<hr>');
      li.append(e_ingredient);
      $('#selected_ingredients').append(li);
      inputd.click(function(){
        dishes_ingredients_list.splice(i, 1);
        make_dishes_list();
      });
    }
  }

  $('#display_button').click(function(){
    if ($('#display_ingredients_id').val() == '') {
      return;
    }
    let ingredient;
    for (let i = 0; i < ingredient_list.length; i++) {
      if (ingredient_list[i]['ingredients_id'] == $('#display_ingredients_id').val()) {
        ingredient = ingredient_list[i];
        break;
      }
    }
    //もし同じIDの食材があったら二重登録しない
    for (let i = 0; i < dishes_ingredients_list.length; i++) {
      if (dishes_ingredients_list[i]['ingredients_id'] == ingredient ['ingredients_id']) {
        return;
      }
    }
    dishes_ingredients_list.push(ingredient['ingredients_id']);
    make_dishes_list();
  });

  function get_ingredients(ingredients_id) {
    for (let i = 0; i < ingredients.length; i++) {
      if (ingredients[i]['ingredients_id'] == ingredients_id) {
        return ingredients[i];
      }
    }
    return false;
  }

  $('#search_dish').click(function(){
    const formData = $('#search_dish_form').serialize();
    const param = {
      method: 'POST',
      headers: {
        'Content-type': 'application/x-www-form-urlencoded',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      body: formData,
    }
    fetch('/ajax/dish', param)
    .then(response => response.json())
    .then(result=> {
      if (result.length == 0) {
        $('#selection_dish').html('<option value="">検索結果はありませんでした</option>');
      } else {
        $('#select_dish').html('<option value="">お料理を選択して下さい</option>');
        for (let i = 0; i < result.length; i++) {
          $('#select_dish').append('<option value="' + result[i]['dishes_id'] + '">' + result[i]['dish_name'] + '</option>');
        }
      }
    })
    .catch((error) => {
      console.error('Error:', error);
    });
  });

  $('#select_dish').change(function(){
    const formData = $('#select_dish_form').serialize();
    const param = {
      method: 'PUT',
      headers: {
        'Content-type':'application/x-www-form-urlencoded',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      body: formData,
    }
    fetch('/ajax/dish', param)
    .then(response => response.json())
    .then(result => {
      $('#dish_name').val(result['dish_name']);
      $('#author_name').text(result['author_name']);
      if (result['white'] == '1') {
        $('#white').prop('checked', true);
      } else {
        $('#white').prop('checked', false);
      }
      if (result['pink'] == '1') {
        $('#pink').prop('checked', true);
      } else {
        $('#pink').prop('checked', false);
      }
      if (result['red'] == '1') {
        $('#red').prop('checked', true);
      } else {
        $('#red').prop('checked', false);
      }
      if (result['green'] == '1') {
        $('#green').prop('checked', true);
      } else {
        $('#green').prop('checked', false);
      }
      if (result['yellowish_green'] == '1') {
        $('#yellowish_green').prop('checked', true);
      } else {
        $('#yellowish_green').prop('checked', false);
      }
      if (result['yellow'] == '1') {
        $('#yellow').prop('checked', true);
      } else {
        $('#yellow').prop('checked', false);
      }
      if (result['beige'] == '1') {
        $('#beige').prop('checked', true);
      } else {
        $('#beige').prop('checked', false);
      }
      if (result['orange'] == '1') {
        $('#orange').prop('checked', true);
      } else {
        $('#orange').prop('checked', false);
      }
      if (result['brown'] == '1') {
        $('#brown').prop('checked', true);
      } else {
        $('#brown').prop('checked', false);
      }
      if (result['purple'] == '1') {
        $('#purple').prop('checked', true);
      } else {
        $('#purple').prop('checked', false);
      }
      if (result['black'] == '1') {
        $('#black').prop('checked', true);
      } else {
        $('#black').prop('checked', false);
      }
      $('#seasoning').val(result['seasoning']);
      $('#dishes_memo').val(result['memo']);
      if (result['mydish'] == '1') {
        if (result['public_private'] == '1') {
          $('input[name=public_private]:eq(1)').prop('checked', true);
        }
        $('#bt_change_submit').show();
      } else {
        $('#bt_change_submit').hide();
      }
      $('#dishes_id').val(result['dishes_id']);
      dishes_ingredients_list = result['manage_ingredients'];
      make_dishes_list();
      $('.abled').prop('disabled', true);
      $('#bt_change').prop('disabled', false);
    })
    .catch((error) => {
      console.error('Error:', error);
    });
  });

  $('#bt_new_submit').click(function(){
    $('#new_change').val('new');
    $('#dish_post').submit();
  });

  $('#bt_change_submit').click(function(){
    $('#new_change').val('change');
    $('#dish_post').submit();
  });

});
</script>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1>お料理登録</h1>
            @isset($errors)
              <div class="alert alert-danger">
              @foreach($errors as $v)
              {{ $v }}<br>
              @endforeach
              </div>
            @endisset
            <div class="card">
                <div class="card-header">お料理の検索</div>

                <div class="card-body">
                
                    <form id="search_dish_form">
                    @csrf
                    <p>
                      <input type="radio" name="mydish" value="1" checked="checked">自分のお料理 表示
                      <input type="radio" name="mydish" value="0">他の人のお料理 表示
                    </p>
                    <p>
                      <p>色でえらぶ(選択しなかった時は全ての色が対象)</p>
                      <p>
                      <input type="checkbox" name="white" id="search_white" value="1">白
                      <input type="checkbox" name="pink" id="search_pink" value="1">桃色
                      <input type="checkbox" name="red" id="search_red" value="1">赤
                      <input type="checkbox" name="green" id="search_green" value="1">緑
                      <input type="checkbox" name="yellowish_green" id="search_yellowish_green" value="1">黄緑
                      <input type="checkbox" name="yellow" id="search_yellow" value="1">黄色
                      </p>
                      <p>
                      <input type="checkbox" name="beige" id="search_beige" value="1">薄橙色(肌色)
                      <input type="checkbox" name="orange" id="search_orange" value="1">橙色
                      <input type="checkbox" name="brown" id="search_brown" value="1">茶
                      <input type="checkbox" name="purple" id="search_purple" value="1">紫
                      <input type="checkbox" name="black" id="search_black" value="1">黒
                    </p>
                    </p>
                    <p>
                      <p>味付けでえらぶ(選択しなかった時は全ての味付けが対象)</p>
                      <p>
                        <select name="seasoning">
                          <option value="">選択</option>
                          <option value="素材の味">素材の味</option>
                          <option value="塩味">塩味</option>
                          <option value="甘味">甘味</option>
                          <option value="甘辛い">甘辛い</option>
                          <option value="甘酸っぱい">甘酸っぱい</option>
                          <option value="苦味">苦味</option>
                          <option value="辛味">辛味</option>
                          <option value="味噌味">味噌味</option>
                          <option value="醤油味">醤油味</option>
                        </select>
                      </p>
                    </p>
                    <p><input type="button" id="search_dish" value="お料理検索"></p>
                    </form>
                    <form id="select_dish_form">
                      <p>
                        <select name="dishes_id" id="select_dish"></select>
                      </p>
                    </form>
                </div>
            </div>

            <p></p>
            <div class="card">
                <div class="card-header">お料理の表示・登録</div>

                <div class="card-body">
                    <form method="post" action="/dish" id="dish_post">
                    @csrf
                    <p>
                      お料理名:<input type="text" name="dish_name" id="dish_name" class="abled">
                      お料理作者:<span id="author_name"></span>
                    </p>
                    <p>
                      <p>お料理の彩り</p>
                      <p>
                      <input type="checkbox" name="white" id="white" value="1" class="abled">白
                      <input type="checkbox" name="pink" id="pink" value="1" class="abled">桃色
                      <input type="checkbox" name="red" id="red" value="1" class="abled">赤
                      <input type="checkbox" name="green" id="green" value="1" class="abled">緑
                      <input type="checkbox" name="yellowish_green" id="yellowish_green" value="1" class="abled">黄緑
                      <input type="checkbox" name="yellow" id="yellow" value="1" class="abled">黄色
                      </p>
                      <p>
                      <input type="checkbox" name="beige" id="beige" value="1" class="abled">薄橙色(肌色)
                      <input type="checkbox" name="orange" id="orange" value="1" class="abled">橙色
                      <input type="checkbox" name="brown" id="brown" value="1" class="abled">茶
                      <input type="checkbox" name="purple" id="purple" value="1" class="abled">紫
                      <input type="checkbox" name="black" id="black" value="1" class="abled">黒
                    </p>
                    </p>
                    <p>
                      <p>お料理の味付け</p>
                      <p>
                        <select name="seasoning" id="seasoning" class="abled">
                          <option value="">選択</option>
                          <option value="素材の味">素材の味</option>
                          <option value="塩味">塩味</option>
                          <option value="甘味">甘味</option>
                          <option value="甘辛い">甘辛い</option>
                          <option value="甘酸っぱい">甘酸っぱい</option>
                          <option value="苦味">苦味</option>
                          <option value="辛味">辛味</option>
                          <option value="味噌味">味噌味</option>
                          <option value="醤油味">醤油味</option>
                        </select>
                      </p>
                    </p>
                    <div class="border border-dark" id="add_ingredient">
                      <p>食材</p>
                      <p>食品目分類:
                        <select id="select_category">
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
                        </select>
                        旬:
                        <select id="select_season">
                          <option value="">旬の指定なし</option>
                          <option value="season1">１月</option>
                          <option value="season2">２月</option>
                          <option value="season3">３月</option>
                          <option value="season4">４月</option>
                          <option value="season5">５月</option>
                          <option value="season6">６月</option>
                          <option value="season7">７月</option>
                          <option value="season8">８月</option>
                          <option value="season9">９月</option>
                          <option value="season10">１０月</option>
                          <option value="season11">１１月</option>
                          <option value="season12">１２月</option>
                        </select>
                        食材名:
                        <select id="select_ingredient">
                        </select>
                      </p>
                      <p>旬</p>
                      <p>
                        <input type="checkbox" id="display_season1" disabled>１月
                        <input type="checkbox" id="display_season2" disabled>２月
                        <input type="checkbox" id="display_season3" disabled>３月
                        <input type="checkbox" id="display_season4" disabled>４月
                        <input type="checkbox" id="display_season5" disabled>５月
                        <input type="checkbox" id="display_season6" disabled>６月
                      </p>
                      <p>
                        <input type="checkbox" id="display_season7" disabled>７月
                        <input type="checkbox" id="display_season8" disabled>８月
                        <input type="checkbox" id="display_season9" disabled>９月
                        <input type="checkbox" id="display_season10" disabled>１０月
                        <input type="checkbox" id="display_season11" disabled>１１月
                        <input type="checkbox" id="display_season12" disabled>１２月
                      </p>
                      <p></p>
                      <p>食材メモ</p>
                      <p><textarea id="display_memo" rows="3" cols="40" disabled></textarea></p>
                      <input type="hidden" id="display_ingredients_id" value="">
                      <p><input type="button" id="display_button" value="上記食材を料理で使う"></p>
                    </div>
                    <ul id="selected_ingredients">
                    </ul>
                    <p>
                      <p>レシピメモ</p>
                      <p><textarea name="memo" id="dishes_memo" rows="3" cols="40" class="abled"></textarea></p>
                    </p>
                    <p>
                      <input type="radio" name="public_private" value="0" checked="checked" class="abled">非公開
                      <input type="radio" name="public_private" value="1" class="abled">公開
                    </p>
                    <input type="hidden" name="dishes_id" id="dishes_id" value="">
                    <input type="hidden" name="new_change" id="new_change" value="">
                    <p><input type="button" id="bt_change" value="新規入力・変更"></p>
                    <p>
                      <input type="button" id="bt_new_submit" value="新規の登録" class="abled">
                      <input type="button" id="bt_change_submit" value="変更の登録" class="abled">
                    </p>
                    </form>
                </div>
            </div>
            <p></p>
            
            <div class="text-right"><a href="/main"><input type="button" value="メイン画面に戻る" class="btn btn-info"></a></div>
        </div>
    </div>
</div>
@endsection

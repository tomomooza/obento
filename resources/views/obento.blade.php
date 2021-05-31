@extends('layouts.app')
<style>
  .p_dishes_ingredients {
    margin-bottom: 0;
  }
  .pic {
    height: 180px;
  }
</style>
<script src="./js/app.js"></script>
<script>
$(function(){
  const ingredients = @json($ingredients_data);
  let ingredient_list = [];//食材一覧
  let dishes_ingredients_list = [];//お料理で使われている食材の一覧
  let dishes_list = [];//お弁当で使われているお料理の一覧
  let obento_date = @json($obento_date);
  const colors = [
    'white',
    'pink',
    'red',
    'green',
    'yellowish_green',
    'yellow',
    'beige',
    'orange',
    'brown',
    'purple',
    'black',
  ];
  $('.abled').prop('disabled', true);
  $('#selected_dish').hide();
  $('#hidden_date').val(obento_date);
  $('#obento_date').val(obento_date);
  get_obento_data();

  function get_obento_data() {
    const formData = $('#obentos_date_form').serialize();
    const param = {
      method: 'POST',
      headers: {
        'Content-type': 'application/x-www-form-urlencoded',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      body: formData,
    }
    fetch('./ajax/obento', param)
    .then(response => response.json())
    .then(result =>{
      console.log(result);
      dishes_list = [];
      for (let i = 0; i < result['dishes'].length; i++) {
        add_dishes_list(result['dishes'][i]);
      }
      display_dishes();
      if (result['photo']!= '') {
        $('#pic').attr('src', result['photo']);
        $('#pic').addClass('pic');
      } else {
        $('#pic').attr('src', '');
        $('#pic').removeClass('pic');
      }
      
      $('#obento_memo').text(result['memo']);
    })
    .catch((error) => {
      console.error('Error:', error);
    });
  }

  $('#obento_date').change(function(){
    $('#hidden_date').val($(this).val());
    get_obento_data();
  });

  $('#image').on('change', function(e){
    let reader = new FileReader();
    reader.onload = function(e) {
      $('#pic').attr('src', e.target.result);
      $('#pic').addClass('pic');
    }
    reader.readAsDataURL(e.target.files[0]);
  });

  function display_dishes() {
    for (let i = 0; i < colors.length; i++) {
      $('#obento_' + colors[i]).prop('checked', false);
    }
    for (let i = 0; i < dishes_list.length; i++) {
      for (let j = 0; j < colors.length; j++) {
        if (dishes_list[i][colors[j]]) {
          $('#obento_' + colors[j]).prop('checked', true);
        }
      }
    }
    $('#obento_dish').html('');
    for (let i = 0; i < dishes_list.length; i++) {
      $('#obento_dish').append(
        '<div class="row">' +
        '<div class="col">' + dishes_list[i]['dish_name'] + '</div>' +
        '<div class="col">' + dishes_list[i]['seasoning'] + '</div>' +
        '<div class="col-2">' +
        '<input type="button" id="delete_dish_' + dishes_list[i]['dishes_id'] + '" value="削除">' +
        '<input type="hidden" name="dishes_id[]" value="' + dishes_list[i]['dishes_id'] + '" >' + 
        '</div>'
      );
      $('#delete_dish_' + dishes_list[i]['dishes_id']).click(function(){
        dishes_list.splice(i, 1);
        display_dishes();
      });
    }
  }

  function add_dishes_list($dish) {
    for (let i = 0; i < dishes_list.length; i++) {
      if (dishes_list[i]['dishes_id'] == $dish['dishes_id']) {
        return;
      }
    }
    dishes_list.push($dish);
  }

  $('#bt_add_dish').click(function() {
    let $dish = [];
    $dish['dishes_id'] = $('#selected_dishes_id').val();
    $dish['dish_name'] = $('#dish_name').val();
    $dish['seasoning'] = $('#seasoning').val();
    for (let i = 0; i < colors.length; i++) {
      $dish[colors[i]] = $('#' + colors[i]).prop('checked');
    }
    add_dishes_list($dish);
    display_dishes();
  });

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

  function make_dishes_list() {
    $('#selected_ingredients').text('');
    for (let i = 0; i < dishes_ingredients_list.length; i++) {
      let ingredient = get_ingredients(dishes_ingredients_list[i]);
      let li = $('<li>');
      let e_ingredient = $('<div>');
      e_ingredient.html(ingredient['ingredient']);
      let inputi = $('<input type="hidden" name="ingredients[]">');
      inputi.val(ingredient['ingredients_id']);
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
    }
  }

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
    fetch('./ajax/dish', param)
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
    fetch('./ajax/dish', param)
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
      $('#selected_dishes_id').val(result['dishes_id']);
      dishes_ingredients_list = result['manage_ingredients'];
      make_dishes_list();
      $('.abled').prop('disabled', true);
      $('#bt_change').prop('disabled', false);
      $('#selected_dish').show();
    })
    .catch((error) => {
      console.error('Error:', error);
    });
  });

});
</script>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1>お弁当登録</h1>
            @isset($errors)
              <div class="alert alert-danger">
              @foreach($errors as $v)
              {{ $v }}<br>
              @endforeach
              </div>
            @endisset
            <div class="card">
                <div class="card-header">お弁当の登録</div>

                <div class="card-body">
                  <form id="obentos_date_form">
                    <input type="hidden" id="hidden_date" name="obento_date" value="">
                  </form>
                  <form action="./obento" method="POST" enctype="multipart/form-data">
                  @csrf
                    <p>お弁当の日付:<input type="date" name="obento_date" id="obento_date"></p>
                    <p>
                      <p>色合い</p>
                      <p>
                          <input type="checkbox" disabled="disabled" name="white" id="obento_white" value="1">白
                          <input type="checkbox" disabled="disabled" name="pink" id="obento_pink" value="1">桃色
                          <input type="checkbox" disabled="disabled" name="red" id="obento_red" value="1">赤
                          <input type="checkbox" disabled="disabled" name="green" id="obento_green" value="1">緑
                          <input type="checkbox" disabled="disabled" name="yellowish_green" id="obento_yellowish_green" value="1">黄緑
                          <input type="checkbox" disabled="disabled" name="yellow" id="obento_yellow" value="1">黄色
                          </p>
                          <p>
                          <input type="checkbox" disabled="disabled" name="beige" id="obento_beige" value="1">薄橙色(肌色)
                          <input type="checkbox" disabled="disabled" name="orange" id="obento_orange" value="1">橙色
                          <input type="checkbox" disabled="disabled" name="brown" id="obento_brown" value="1">茶
                          <input type="checkbox" disabled="disabled" name="purple" id="obento_purple" value="1">紫
                          <input type="checkbox" disabled="disabled" name="black" id="obento_black" value="1">黒
                        </p>
                    </p>
                    <p>
                      <p>お料理</p>
                      <div id="obento_dish"></div>
                    </p>
                    <p>
                      <p>写真<input type="file" name="image" id="image" accept="image/png, image/jpeg"></p>
                      <div><img id="pic"></div>
                    </p>
                    <p>
                      <p>お弁当メモ</p>
                      <p><textarea name="obento_memo" id="obento_memo" rows="3" cols="40" ></textarea></p>
                    </p>
                    <p><input type="submit" value="このお弁当をメニューに登録する"></p>
                  </form>
                </div>
            </div>

            <p></p>

            <div class="card">
            
                <div class="card-header">お料理の検索</div>

                <div class="card-body">
                
                    <form id="search_dish_form">
                    @csrf
                  
                      <input type="hidden" name="mydish" value="1" >
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
                
                    <div id="selected_dish">
                    <hr>
                    <p><input type="button" id="bt_add_dish" value="このお料理をお弁当に使う"></p>
                    <p>
                      お料理名:<input type="text" name="dish_name" id="dish_name" disabled="disabled">
                    </p>
                    <p>
                      <p>お料理の彩り</p>
                      <p>
                      <input type="checkbox" name="white" id="white" value="1" disabled="disabled">白
                      <input type="checkbox" name="pink" id="pink" value="1" disabled="disabled">桃色
                      <input type="checkbox" name="red" id="red" value="1" disabled="disabled">赤
                      <input type="checkbox" name="green" id="green" value="1" disabled="disabled">緑
                      <input type="checkbox" name="yellowish_green" id="yellowish_green" value="1" disabled="disabled">黄緑
                      <input type="checkbox" name="yellow" id="yellow" value="1" disabled="disabled">黄色
                      </p>
                      <p>
                      <input type="checkbox" name="beige" id="beige" value="1" disabled="disabled">薄橙色(肌色)
                      <input type="checkbox" name="orange" id="orange" value="1" disabled="disabled">橙色
                      <input type="checkbox" name="brown" id="brown" value="1" disabled="disabled">茶
                      <input type="checkbox" name="purple" id="purple" value="1" disabled="disabled">紫
                      <input type="checkbox" name="black" id="black" value="1" disabled="disabled">黒
                    </p>
                    </p>
                    <p>
                      <p>お料理の味付け</p>
                      <p>
                        <select name="seasoning" id="seasoning" disabled="disabled">
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
                    
                    <ul id="selected_ingredients">
                    </ul>
                    <p>
                      <p>レシピメモ</p>
                      <p><textarea id="dishes_memo" rows="3" cols="40" disabled="disabled"></textarea></p>
                    </p>
                    
                    <input type="hidden" id="selected_dishes_id" value="">
                    </div>
                </div>
            </div>
            <p></p>
            
            <div class="text-right"><a href="./main"><input type="button" value="メイン画面に戻る" class=""></a></div>
        </div>
    </div>
</div>
@endsection

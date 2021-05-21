@extends('layouts.app')
<style>
  .p_dishes_ingredients {
    margin-bottom: 0;
  }
  #pic {
    height: 180px;
  }
</style>
<script src="/js/app.js"></script>
<script>
$(function(){
  const obentos = @json($obentos_data);
  console.log(obentos);
  const start_year = 2021;
  const end_year = (new Date()).getFullYear();
  for (let i = start_year; i <= end_year; i++) {
    $('#year').append('<option value="'+i+'">' + i + '</option>');
  }
  $('#year option:last').prop('selected', true);
});
</script>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1>お弁当メニュー</h1>
            @isset($errors)
              <div class="alert alert-danger">
              @foreach($errors as $v)
              {{ $v }}<br>
              @endforeach
              </div>
            @endisset

            <div>
              <form action="/menu" method="post"></form>
                年月を選択して下さい:
                <select id="year" name="year"></select>年　
                <select id="month" name="month">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                  <option value="11">11</option>
                  <option value="12">12</option>
                </select>月　
                <input type="submit" value="検索">
              </form>
            </div>

            <div class="card">
                <div class="card-header">お弁当の登録</div>

                <div class="card-body">
                  
                    <p>お弁当の日付:<span></span></p>
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
                      <p>写真</p>
                      <div><img id="pic"></div>
                    </p>
                    <p>
                      <p>お弁当メモ</p>
                      <p><textarea name="obento_memo" id="obento_memo" rows="3" cols="40" disabled="disabled"></textarea></p>
                    </p>
                </div>
            </div>

            <p></p>

            <div class="text-right"><a href="/main"><input type="button" value="メイン画面に戻る" class=""></a></div>
        </div>
    </div>
</div>
@endsection

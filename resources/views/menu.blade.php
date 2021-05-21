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
  const year = @json($year);
  const month = @json($month);
  
  const start_year = 2000;
  const end_year = (new Date()).getFullYear();
  for (let i = start_year; i <= end_year; i++) {
    $('#year').append('<option value="'+i+'">' + i + '</option>');
  }
  $('select#year option[value="'+year+'"]').prop('selected', true);
  $('select#month option[value="'+month+'"]').prop('selected', true);


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
              <form action="/menu" method="post">
                @csrf
                年月を選択して下さい:
                <select id="year" name="year"></select>年　
                <select id="month" name="month">
                  <option value="01">1</option>
                  <option value="02">2</option>
                  <option value="03">3</option>
                  <option value="04">4</option>
                  <option value="05">5</option>
                  <option value="06">6</option>
                  <option value="07">7</option>
                  <option value="08">8</option>
                  <option value="09">9</option>
                  <option value="10">10</option>
                  <option value="11">11</option>
                  <option value="12">12</option>
                </select>月　
                <input type="submit" value="検索">
              </form>
            </div>

            @foreach($obentos_data as $obento)
            <p></p>
            <div class="card">
                <div class="card-header">お弁当の登録</div>

                <div class="card-body">
                  
                    <p>お弁当の日付:　<span>{{date('Y年n月j日', strtotime($obento['obento_date']))}}</span></p>
                    <p>
                      <p>色合い</p>
                      <p>
                          @if ($obento['white'] == 1) 
                            白　
                          @endif
                          @if ($obento['pink'] == 1) 
                            桃色　
                          @endif
                          @if ($obento['red'] == 1) 
                            赤　
                          @endif
                          @if ($obento['green'] == 1) 
                            緑　
                          @endif
                          @if ($obento['yellowish_green'] == 1) 
                            黄緑　
                          @endif
                          @if ($obento['yellow'] == 1) 
                            黄　
                          @endif
                          @if ($obento['beige'] == 1) 
                            薄橙色　
                          @endif
                          @if ($obento['orange'] == 1) 
                            橙色　
                          @endif
                          @if ($obento['brown'] == 1) 
                            茶　
                          @endif
                          @if ($obento['purple'] == 1) 
                            紫　
                          @endif
                          @if ($obento['black'] == 1) 
                            黒　
                          @endif    
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
            @endforeach
            <p></p>

            <div class="text-right"><a href="/main"><input type="button" value="メイン画面に戻る" class=""></a></div>
        </div>
    </div>
</div>
@endsection

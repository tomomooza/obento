@extends('layouts.app')
<style>
  .p_0 {
    margin-bottom: 0;
  }
  .pic {
    height: 180px;
  }
  .ly_inline {
    display: inline-block;
  }

 /* colorSetting */
.white {
  border: solid 1px black;
  background-color: white;
  width: 14px;
  height: 14px;
  display: inline-block;
}
.pink {
  border: solid 1px pink;
  background-color: white;
  width: 14px;
  height: 14px;
  display: inline-block;
}
.red {
  border: solid 1px black;
  background-color: red;
  width: 14px;
  height: 14px;
  display: inline-block;
}
.green {
  border: solid 1px black;
  background-color: green;
  width: 14px;
  height: 14px;
  display: inline-block;
}
.yellowish_green {
  border: solid 1px black;
  background-color: lawngreen;
  width: 14px;
  height: 14px;
  display: inline-block;
}
.yellow {
  border: solid 1px black;
  background-color: yellow;
  width: 14px;
  height: 14px;
  display: inline-block;
}
.beige {
  border: solid 1px black;
  background-color: wheat;
  width: 14px;
  height: 14px;
  display: inline-block;
}
.orange {
  border: solid 1px black;
  background-color: orange;
  width: 14px;
  height: 14px;
  display: inline-block;
}
.brown {
  border: solid 1px black;
  background-color: saddlebrown;
  width: 14px;
  height: 14px;
  display: inline-block;
}
.purple {
  border: solid 1px black;
  background-color: purple;
  width: 14px;
  height: 14px;
  display: inline-block;
}
.black {
  border: solid 1px black;
  background-color: black;
  width: 14px;
  height: 14px;
  display: inline-block;
}


</style>
<script src="./js/app.js"></script>
<script>
$(function(){
  const obentos = @json($obentos_data);
  const year = @json($year);
  const month = @json($month);
  
  const start_year = 2021;
  const end_year = (new Date()).getFullYear() + 1;
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
            <h1>?????????????????????</h1>
            @isset($errors)
              <div class="alert alert-danger">
              @foreach($errors as $v)
              {{ $v }}<br>
              @endforeach
              </div>
            @endisset

            <div>
              <form action="./menu" method="post">
                @csrf
                ??????????????????????????????:
                <select id="year" name="year"></select>??????
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
                </select>??????
                <input type="submit" value="??????">
              </form>
            </div>

            @foreach($obentos_data as $obento)
            <p></p>
            <div class="card">
                <div class="card-header">??????????????????:???<span>{{date('Y???n???j???', strtotime($obento['obento_date']))}}</span></div>

                <div class="card-body">
                  
                    
                    <p class="ly_inline mb-0">?????????:???
                          @if ($obento['white'] == 1) 
                            <div class="white"></div>??????
                          @endif
                          @if ($obento['pink'] == 1) 
                            <div class="pink"></div>?????????
                          @endif
                          @if ($obento['red'] == 1) 
                            <div class="red"></div>??????
                          @endif
                          @if ($obento['green'] == 1) 
                            <div class="green"></div>??????
                          @endif
                          @if ($obento['yellowish_green'] == 1) 
                            <div class="yellowish_green"></div>?????????
                          @endif
                          @if ($obento['yellow'] == 1) 
                            <div class="yellow"></div>??????
                          @endif
                          @if ($obento['beige'] == 1) 
                            <div class="beige"></div>????????????
                          @endif
                          @if ($obento['orange'] == 1) 
                            <div class="orange"></div>?????????
                          @endif
                          @if ($obento['brown'] == 1) 
                            <div class="brown"></div>??????
                          @endif
                          @if ($obento['purple'] == 1) 
                            <div class="purple"></div> ??????
                          @endif
                          @if ($obento['black'] == 1) 
                            <div class="black"></div>??????
                          @endif    
                    </p>
                      <p class="mb-0">?????????</p>
                      @foreach($obento['dishes'] as $d)
                      <div class="row">
                        <div class="col">???{{$d['dish_name']}}</div>
                        <div class="col-6">{{$d['seasoning']}}</div>
                      </div>
                      @endforeach
                    <p>
                      <p class="mb-0">??????</p>
                      @if($obento['photo']!='')
                      <div><img class="pic" src="{{$obento['photo']}}"></div>
                      @endif
                    </p>
                      <p class="mb-0">???????????????</p>
                      <p class="mb-0"><textarea name="obento_memo" id="obento_memo" rows="3" cols="40" disabled="disabled">{{$obento['memo']}}</textarea></p>
                </div>
            </div>
            @endforeach
            <p></p>

            <div class="text-right"><a href="./main"><input type="button" value="????????????????????????" class=""></a></div>
        </div>
    </div>
</div>
@endsection

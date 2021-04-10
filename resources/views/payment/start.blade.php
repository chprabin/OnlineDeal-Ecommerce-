<form action="https://perfectmoney.is/api/step1.asp" method='POST' class='payment-form'>
 @foreach($data as $n=>$v)
  <input type="hidden" name="{{$n}}" value="{{$v}}">
 @endforeach
 <input type="submit" style='display:none;'>
</form>
<script>
 var form=document.querySelector('form.payment-form');
 form.submit();
</script>
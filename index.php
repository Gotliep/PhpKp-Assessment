<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Unit Converter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  </head>
  <body>
  	<div class="container px-5 my-5">
	    <form id="contactForm">    
	        <div class="form-floating mb-3">
	            <select class="form-select" id="conversion" aria-label="Type Of Conversion" onchange="getUnitTypes()">

	            </select>
	            <label for="newField1">Type Of Conversion</label>
	        </div>
	        
	        
	        <div class="form-floating mb-3">
	            <select class="form-select" id="from" aria-label="From">
	
	            </select>
	            <label for="newField4">From</label>
	        </div>
	        
	        
	        <div class="form-floating mb-3">
	            <select class="form-select" id="to" aria-label="To">
	
	            </select>
	            <label for="newField7">To</label>
	        </div>
	              
	        <div class="form-floating mb-3">
	            <input class="form-control" id="amount" type="text" placeholder="Amount" data-sb-validations="required" />
	            <label for="amount">Amount</label>
	            <div class="invalid-feedback" data-sb-feedback="amount:required">Amount is required.</div>
	        </div>
	        
	        <div class="form-floating mb-3">
	            <input class="form-control" id="answer" type="text" readonly/>
	            <label for="newField5">Answer</label>
	        </div>
	        
	        <div class="d-grid">
	            <button class="btn btn-primary btn-lg" onclick="calculate()" type="button">Submit</button>
	        </div>
	    </form>
	</div>
	<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  	
  	
  	<script>
	  	$( document ).ready(function() {
	  		get_types();
	  	});
	  	
	  	function get_types(){
	  		$.ajax({
				url:'api.php',
				type: "POST",
				dataType: "json",
				data: {
					'action' : "get_types"
				},
				success:function(data){
					$('#conversion option').remove();
					$('#conversion').append(new Option("None",""));
					$.each( data, function( key, value ) {
						$('#conversion').append(new Option(value,value));
					});
				}
			});
	  	}
	  	
	  	function getUnitTypes(){
	  		$.ajax({
				url:'api.php',
				type: "POST",
				dataType: "json",
				data: {
					'action' : "get_unit_types",
					'conversion' : $('#conversion').val()
				},
				success:function(data){
						$('#to option').remove();
						$('#from option').remove();

						$('#to').append(new Option("None",""));
						$('#from').append(new Option("None",""));
					
						$.each( data, function( key, value ) {
							$('#to').append(new Option(value,value));
							$('#from').append(new Option(value,value));
						});
					}
				});
	  	}

	  	function calculate(){
	  		$.ajax({
					url:'api.php',
					type: "POST",
					dataType: "json",
					data: {
						'action' : "calculate",
						'amount' : $('#amount').val(),
						'from' : $('#from').val(),
						'to' : $('#to').val()
					},
					success:function(data){
						$('#answer').val(data.answer);
					}
				});
	  	}
  	</script>
  
  
    </body>
</html>
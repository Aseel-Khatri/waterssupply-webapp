<style>
    #form-control {
    display: block;
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
    -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
}

</style>
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                   
                </div>              
                 <!-- /. ROW  -->
                  <hr />
             <h1 style ="color: #084365;">Expenses</h1>
    <br>
    
    
   
    
<!--    <form method="POST" action="<?php echo base_url()?>Maincontroller/expense_auth">-->
<form onsubmit="return false">
          	<div class="table-responsive">
		 <TABLE id="dataTable" class="table   table-condensed table-striped table-hover table-bordered">
		                 <tr>
		             
		             <!--	<td>
		             		<INPUT type="checkbox"  name="chk"/>

		             	</td>-->
		             	
		             	
		             	 <td>
		             	    <span style="font-weight: bold;">Expanse name</span><br/>	
		             	     <input required="required" style = "height: 31px;border-radius: 6px;" type="text" name="username[]" >
		                    
		             	     </td>
		             
		             	
						<td><button type="button" onclick="addRow('dataTable')" class="btn btn-default btn-sm">
                <span class="glyphicon glyphicon-plus form-control"></span> 

                </button><!--<button type="button" onclick="deleteRow('dataTable')" class="btn btn-default btn-sm">
                <span class="glyphicon glyphicon-trash form-control"></span></button>--></td>
		             </tr>
		             <tr>
		                <!-- <td>
		             		<INPUT type="checkbox"  name="chk"/>

		             	</td>-->
		             	
		             	
		             	<td>
		             	    <span style="font-weight: bold;">Price</span><br/>
		             	     	
		             	     <input required="required" style = "height: 31px;border-radius: 6px;" type="number" name="price[]" >
		                    
		             	     </td>
		             
		                 </tr>
     
      
    </table>
    <input type="submit"  style="height: 45px;" class="sub btn btn-info form-control" value="Save">  
</div>
</form>
<script>
		function addRow(tableID) {

			var table = document.getElementById(tableID);
         
			var rowCount = table.rows.length;
			var row = table.insertRow(rowCount);

			/*var cell1 = row.insertCell(0);
			var element1 = document.createElement("input");
			element1.type = "checkbox";
			element1.name="chkbox[]";

			cell1.appendChild(element1);
*/		
            
			var cell3 = row.insertCell(0);
			var element2 = document.createElement("input");
			element2.type = "text";
			element2.name = "username[]";
			element2.style = "height: 31px;border-radius: 6px;";
            
            var elementh = document.createElement("th");
			elementh.innerHTML = "Expense name";
            cell3.appendChild(elementh);  
            cell3.appendChild(element2);
   
//row2
	
			var table2 = document.getElementById(tableID);
         
			var rowCount2 = table2.rows.length;
			var row2 = table2.insertRow(rowCount2);

		/*	var cell12 = row2.insertCell(0);
			var element12 = document.createElement("input");
			element12.type = "checkbox";
			element12.name="chkbox[]";

			cell12.appendChild(element12);
		*/
            
			var cell32 = row2.insertCell(0);
			var element22 = document.createElement("input");
			element22.type = "number";
			element22.name = "price[]";
			element22.style = "height: 31px;border-radius: 6px;";
            
            var elementh2 = document.createElement("th");
			elementh2.innerHTML = "Price";
            cell32.appendChild(elementh2);  
            cell32.appendChild(element22);

			 

		}

		function deleteRow(tableID) {
			try {
			var table = document.getElementById(tableID);
			var rowCount = table.rows.length;

			for(var i=0; i<rowCount; i++) {
				var row = table.rows[i];
				var chkbox = row.cells[0].childNodes[0];
				if(null != chkbox && true == chkbox.checked) {
					table.deleteRow(i);
					rowCount--;
					i--;
				}


			}
			}catch(e) {
				alert(e);
			}
		}

	</script>
	
	
	<script>
	    $('.sub').click(function(){
	       /*var name=$('input[name="username"]').map(function(){
	           return this.value;
	       }).get();*/
	       var name=$( "form" ).serialize();
	      /* alert(name);*/
	          var myurl='https://watersupply-soft.com/maincontroller/expense_auth';
   $.ajax({
          url: myurl,
          method:'POST',
          data: name,
           success: function(result){ 
            if(result==1)
            {
                alert("Sucessfully registered..");
                location.reload();
            }
           }
   });
	        
	    });
	</script>
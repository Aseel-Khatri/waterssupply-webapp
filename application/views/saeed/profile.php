<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!--order_delivery_modal-->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>


<link href=="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">

        <div id="page-wrapper" >

            <div id="page-inner">

                <div class="row">

                    <div class="col-md-12">

                     <h2>Admin Dashboard

                     </h2>   

                        <h5>Welcome Jhon Deo , Love to see you back. </h5>

                    </div>

                </div>              

                 <!-- /. ROW  -->

                  <hr />

             <h1  style="color: #084365;">Profile</h1>

    <br>

    <div id="mydiv">

    <div class="table-responsive">

    <table  id="myTableee" class="table   table-condensed table-striped  table-hover  ">
<thead>
      <tr>

          <th>SR</th>

          <th>User name</th>
          <th>Password</th>
          

      </tr>

     </thead>
<tbody>
        <?php

         $index=1;

         if(empty($data))

         {

          echo '<h1>There is no record</h1>';

         }

         else

         {

          foreach ($data as $a) {

          ?>

      <tr>

         <td><?php echo $index++?></td>

        

         

          <td><?php echo $a->user_name?></td>
          <td><?php echo $a->password?></td>
          

</td>
<!--
          <td>
<button data-key="<?echo $a->delivery_boy_idd?>"   onClick='edit_day_user(this)' class='btn btn-info btn-xs'><span class='glyphicon glyphicon-edit'> Edit </span></button >&nbsp;<button data-key="<?echo $a->delivery_boy_idd?>" onClick='day_delete(this)' class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-trash'> Delete </span></button >
          </td>-->

      </tr>

      <?php

    }

  }

    ?>

      

      
</tbody>
     

      

    </table>

</div>

</div>

</div>

</div>

</div>

</div>

